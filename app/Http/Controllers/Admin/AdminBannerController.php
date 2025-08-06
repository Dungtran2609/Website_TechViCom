<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\StoreBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use App\Models\Banner;

class AdminBannerController extends Controller
{
    public function index()
    {
        $query = Banner::query();

        // Tìm kiếm theo từ khóa (link)
        if (request('keyword')) {
            $query->where('link', 'like', '%' . request('keyword') . '%');
        }

        // Lọc theo trạng thái
        if (request('status')) {
            $banners = $query->orderBy('start_date')->orderBy('end_date')->get()->filter(function($banner) {
                return $banner->status == request('status');
            });
            // Chuyển collection filter thành LengthAwarePaginator để phân trang thủ công
            $page = request('page', 1);
            $perPage = 5;
            $items = $banners->forPage($page, $perPage);
            $banners = new \Illuminate\Pagination\LengthAwarePaginator($items, $banners->count(), $perPage, $page, [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);
            return view('admin.banner.index', compact('banners'));
        }

        // Lọc theo ngày bắt đầu
        if (request('start_date_from')) {
            $query->whereDate('start_date', '>=', request('start_date_from'));
        }
        if (request('start_date_to')) {
            $query->whereDate('start_date', '<=', request('start_date_to'));
        }
        // Lọc theo ngày kết thúc
        if (request('end_date_from')) {
            $query->whereDate('end_date', '>=', request('end_date_from'));
        }
        if (request('end_date_to')) {
            $query->whereDate('end_date', '<=', request('end_date_to'));
        }

        $banners = $query->orderBy('stt', 'asc')->paginate(5);
        return view('admin.banner.index', compact('banners'));
    }

    public function create()
    {
        if (\App\Models\Banner::count() >= 3) {
            // Quay về trang index và hiển thị thông báo
            return redirect()
                ->route('admin.banner.index') // Đổi route này thành route index banner của bạn
                ->with('error', 'Đã đủ 3 banner, không thể thêm mới.');
        }

        return view('admin.banner.create');
    }

    public function store(StoreBannerRequest $request)
    {
        $data = $request->validated();
        $data['image'] = $request->file('image')->store('banners', 'public');
        $data['stt'] = Banner::max('stt') + 1;
        Banner::create($data);
        return redirect()->route('admin.banner.index')->with('success', 'Thêm banner thành công');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact('banner'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        } else {
            unset($data['image']);
        }
        $banner->update($data);
        return redirect()->route('admin.banner.index')->with('success', 'Cập nhật banner thành công');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        $this->updateBannerOrder();
        return redirect()->route('admin.banner.index')->with('success', 'Xóa banner thành công');
    }

    // Hàm cập nhật lại stt cho toàn bộ banner
    private function updateBannerOrder()
    {
        $banners = Banner::orderBy('stt', 'asc')->orderBy('id', 'asc')->get();
        $i = 1;
        foreach ($banners as $banner) {
            $banner->update(['stt' => $i++]);
        }
    }
}