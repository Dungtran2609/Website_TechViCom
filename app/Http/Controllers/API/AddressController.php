<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AddressController extends Controller
{
    /**
     * API gốc dữ liệu hành chính VN (tỉnh/quận/phường)
     */
    protected string $base = 'https://provinces.open-api.vn/api';

    /** TTL cache (giây) */
    private const TTL = 86400; // 24h

    /** Trả về đúng 1 tỉnh/thành: Hà Nội */
    public function getProvinces()
    {
        return response()->json([
            ['code' => '01', 'name' => 'Thành phố Hà Nội', 'slug' => 'hanoi'],
        ], Response::HTTP_OK);
    }

    /**
     * Danh sách quận/huyện của Hà Nội
     * $provinceCode: nhận 'hanoi' | 'ha-noi' | '01'
     */
    public function getDistricts($provinceCode)
    {
        $code = $this->normalizeProvinceCode((string) $provinceCode);
        if ($code !== '01') {
            // Chỉ phục vụ Hà Nội
            return response()->json([], Response::HTTP_OK);
        }

        try {
            $data = Cache::remember("hn:districts:v2", self::TTL, function () use ($code) {
                $res = Http::timeout(10)->get($this->base . "/p/{$code}", ['depth' => 2]);
                if ($res->failed()) {
                    throw new \RuntimeException('Không lấy được danh sách quận/huyện');
                }
                return $res->json();
            });

            $districts = collect($data['districts'] ?? [])
                ->map(fn($d) => [
                    'code' => (string) ($d['code'] ?? ''),      // ví dụ '001'
                    'name' => (string) ($d['name'] ?? ''),      // Quận Ba Đình
                    'slug' => Str::slug($d['name'] ?? ''),      // quan-ba-dinh
                    'division_type' => $d['division_type'] ?? null,      // Quận/Huyện/Thị xã
                ])
                ->values()
                ->all();

            return response()->json($districts, Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tải quận/huyện',
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * Danh sách phường/xã theo quận/huyện (chỉ cho HN)
     * $districtCode: mã quận (vd: '001') hoặc slug (vd: 'ba-dinh')
     */
    public function getWards($districtCode)
    {
        $districtCode = $this->resolveDistrictCode((string) $districtCode);
        if (!$districtCode) {
            return response()->json([], Response::HTTP_OK);
        }

        try {
            $data = Cache::remember("hn:wards:{$districtCode}:v2", self::TTL, function () use ($districtCode) {
                $res = Http::timeout(10)->get($this->base . "/d/{$districtCode}", ['depth' => 2]);
                if ($res->failed()) {
                    throw new \RuntimeException('Không lấy được danh sách phường/xã');
                }
                return $res->json();
            });

            $wards = collect($data['wards'] ?? [])
                ->map(fn($w) => [
                    'code' => (string) ($w['code'] ?? ''),      // ví dụ '00001'
                    'name' => (string) ($w['name'] ?? ''),      // Phường Phúc Xá
                    'slug' => Str::slug($w['name'] ?? ''),      // phuong-phuc-xa
                    'division_type' => $w['division_type'] ?? null,      // Phường/Xã/Thị trấn
                ])
                ->values()
                ->all();

            return response()->json($wards, Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tải phường/xã',
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_GATEWAY);
        }
    }

    /* ---------------- Helpers ---------------- */

    /** Chuẩn hoá mã tỉnh: 'hanoi' | 'ha-noi' | '01' -> '01' */
    protected function normalizeProvinceCode(string $code): string
    {
        $code = strtolower(trim($code));
        return ($code === 'hanoi' || $code === 'ha-noi' || $code === '01') ? '01' : $code;
    }

    /**
     * Nhận districtCode hoặc slug và trả về mã quận/huyện chính thức thuộc Hà Nội.
     * Dựa trên cache từ getDistricts().
     */
    protected function resolveDistrictCode(string $input): ?string
    {
        $input = strtolower(trim($input));

        try {
            // Lấy danh sách quận/huyện HN đã cache
            $districts = Cache::remember("hn:districts:v2", self::TTL, function () {
                $res = Http::timeout(10)->get($this->base . "/p/01", ['depth' => 2]);
                if ($res->failed()) {
                    throw new \RuntimeException('Không lấy được danh sách quận/huyện');
                }
                $data = $res->json();
                return collect($data['districts'] ?? [])->map(function ($d) {
                    return [
                        'code' => (string) ($d['code'] ?? ''),
                        'name' => (string) ($d['name'] ?? ''),
                        'slug' => Str::slug($d['name'] ?? ''),
                    ];
                })->values()->all();
            });
        } catch (\Throwable $e) {
            // Nếu lỗi, trả null để API trả mảng rỗng
            return null;
        }

        // Nếu truyền đúng mã số (vd '001'), trả luôn
        if (preg_match('/^\d+$/', $input) && collect($districts)->firstWhere('code', $input)) {
            return $input;
        }

        // Nếu truyền slug (vd 'ba-dinh'), map về code
        $found = collect($districts)->firstWhere('slug', Str::slug($input));
        return $found['code'] ?? null;
    }
}
