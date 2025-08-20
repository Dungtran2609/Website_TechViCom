<?php
namespace App\Http\Controllers\Admin\Logo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Logo;
use Illuminate\Support\Facades\Storage;

class AdminLogoController extends Controller
{
    // Hiển thị danh sách logo
    public function index()
    {
        $logos = Logo::orderByDesc('created_at')->get();
        return view('admin.logos.index', compact('logos'));
    }

    // Form thêm logo
    public function create()
    {
        return view('admin.logos.create');
    }

    // Lưu logo mới
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:admin,client',
            'logo' => 'required|image|max:2048',
            'alt' => 'nullable|string|max:255',
        ]);
        $path = $request->file('logo')->store('logos', 'public');
        Logo::create([
            'type' => $request->type,
            'path' => $path,
            'alt' => $request->alt,
        ]);
        return redirect()->route('admin.logos.index')->with('success', 'Đã thêm logo mới!');
    }

    // Form sửa logo
    public function edit($id)
    {
        $logo = Logo::findOrFail($id);
        return view('admin.logos.edit', compact('logo'));
    }

    // Cập nhật logo
    public function update(Request $request, $id)
    {
        $logo = Logo::findOrFail($id);
        $request->validate([
            'type' => 'required|in:admin,client',
            'logo' => 'nullable|image|max:2048',
            'alt' => 'nullable|string|max:255',
        ]);
        $data = [
            'type' => $request->type,
            'alt' => $request->alt,
        ];
        if ($request->hasFile('logo')) {
            // Xoá file cũ
            if ($logo->path && Storage::disk('public')->exists($logo->path)) {
                Storage::disk('public')->delete($logo->path);
            }
            $data['path'] = $request->file('logo')->store('logos', 'public');
        }
        $logo->update($data);
        return redirect()->route('admin.logos.index')->with('success', 'Đã cập nhật logo!');
    }

    // Xoá logo
    public function destroy($id)
    {
        $logo = Logo::findOrFail($id);
        if ($logo->path && Storage::disk('public')->exists($logo->path)) {
            Storage::disk('public')->delete($logo->path);
        }
        $logo->delete();
        return redirect()->route('admin.logos.index')->with('success', 'Đã xoá logo!');
    }
}
