<?php

namespace App\Http\Controllers\Client\Address;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ClientAddressController extends Controller
{
    /**
     * API nguồn dữ liệu hành chính VN
     */
    protected string $base = 'https://provinces.open-api.vn/api';

    /**
     * GET /api/provinces
     * Chỉ trả về Hà Nội để khớp validate phía BE.
     */
    public function getProvinces()
    {
        try {
            return response()->json([
                ['code' => '01', 'name' => 'Thành phố Hà Nội'],
            ]);
        } catch (\Throwable $e) {
            return response()->json([]);
        }
    }

    /**
     * GET /api/districts/{provinceCode}
     * Trả danh sách quận/huyện của Hà Nội (code '01').
     * $provinceCode có thể là '01', 'hanoi', 'ha-noi'
     */
    public function getDistricts($provinceCode)
    {
        try {
            $code = $this->normalizeProvinceCode($provinceCode);
            if ($code !== '01') {
                // Không hỗ trợ tỉnh khác
                return response()->json([]);
            }

            // /p/{code}?depth=2 -> trả tỉnh + districts
            $data = Cache::remember("hn:districts:depth2:v1", 86400, function () use ($code) {
                $res = Http::timeout(10)->get($this->base . "/p/{$code}", ['depth' => 2]);
                if ($res->failed()) {
                    abort(Response::HTTP_BAD_GATEWAY, 'Không lấy được danh sách quận/huyện');
                }
                return $res->json();
            });

            $districts = collect($data['districts'] ?? [])
                ->map(fn($d) => [
                    'code' => (string) ($d['code'] ?? ''),
                    'name' => $d['name'] ?? '',
                ])
                ->values()
                ->all();

            return response()->json($districts);
        } catch (\Throwable $e) {
            return response()->json([]);
        }
    }

    /**
     * GET /api/wards/{districtCodeOrSlug}
     * Trả danh sách phường/xã theo quận/huyện thuộc Hà Nội.
     * Cho phép truyền code (vd: '001') hoặc slug tên (vd: 'ba-dinh').
     */
    public function getWards($districtCode)
    {
        try {
            // Hỗ trợ truyền slug -> map về code
            $districtCode = $this->resolveDistrictCode($districtCode);
            if (!$districtCode) {
                return response()->json([]);
            }

            // /d/{code}?depth=2 -> trả quận + wards
            $data = Cache::remember("hn:wards:{$districtCode}:depth2:v1", 86400, function () use ($districtCode) {
                $res = Http::timeout(10)->get($this->base . "/d/{$districtCode}", ['depth' => 2]);
                if ($res->failed()) {
                    abort(Response::HTTP_BAD_GATEWAY, 'Không lấy được danh sách phường/xã');
                }
                return $res->json();
            });

            $wards = collect($data['wards'] ?? [])
                ->map(fn($w) => [
                    'code' => (string) ($w['code'] ?? ''),
                    'name' => $w['name'] ?? '',
                ])
                ->values()
                ->all();

            return response()->json($wards);
        } catch (\Throwable $e) {
            return response()->json([]);
        }
    }

    /* ================= Helpers ================= */

    /** Chuẩn hoá mã tỉnh: '01' | 'hanoi' | 'ha-noi' -> '01' */
    protected function normalizeProvinceCode(string $code): string
    {
        $code = strtolower(trim($code));
        return ($code === '01' || $code === 'hanoi' || $code === 'ha-noi') ? '01' : $code;
    }

    /**
     * Nhận 'districtCode' hoặc slug tên quận và trả về mã quận hợp lệ của Hà Nội.
     * Dùng cache từ getDistricts().
     */
    protected function resolveDistrictCode(string $input): ?string
    {
        $input = strtolower(trim($input));

        // Lấy danh sách quận Hà Nội (đã cache)
        $districts = Cache::remember("hn:districts:list:v1", 86400, function () {
            $res = Http::timeout(10)->get($this->base . "/p/01", ['depth' => 2]);
            if ($res->failed()) {
                abort(Response::HTTP_BAD_GATEWAY, 'Không lấy được danh sách quận/huyện');
            }
            $data = $res->json();
            return collect($data['districts'] ?? [])->map(function ($d) {
                return [
                    'code' => (string) ($d['code'] ?? ''),
                    'name' => $d['name'] ?? '',
                    'slug' => Str::slug($d['name'] ?? ''),
                ];
            })->values()->all();
        });

        // Nếu chuỗi là số và tồn tại trong danh sách -> trả luôn
        if (preg_match('/^\d+$/', $input) && collect($districts)->firstWhere('code', $input)) {
            return $input;
        }

        // Nếu là slug tên -> map về code
        $found = collect($districts)->firstWhere('slug', Str::slug($input));
        return $found['code'] ?? null;
    }
}
