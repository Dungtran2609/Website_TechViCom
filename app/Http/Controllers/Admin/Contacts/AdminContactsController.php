<?php

namespace App\Http\Controllers\Admin\Contacts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class AdminContactsController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        // Tìm kiếm theo từ khóa
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%")
                    ->orWhere('phone', 'like', "%$keyword%")
                    ->orWhere('message', 'like', "%$keyword%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sắp xếp liên hệ mới nhất lên đầu
        $contacts = $query->orderBy('created_at', 'desc')->paginate();

        return view('admin.contacts.index', compact('contacts'));
    }


    public function show($id)
    {
        $contact = Contact::with('handledByUser')->findOrFail($id);

        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.contacts.show', compact('contact'));
    }


    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Đã xoá liên hệ thành công.');
    }

    public function markAsHandled(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,responded,rejected',
        ]);

        $contact = Contact::findOrFail($id);

        $currentStatus = $contact->status;
        $newStatus = $request->status;

        // Ma trận trạng thái hợp lệ
        $allowedTransitions = [
            'pending' => ['in_progress'],
            'in_progress' => ['responded', 'rejected'],
            'responded' => [], // không được chuyển tiếp
            'rejected' => [],    // không được chuyển tiếp
        ];

        // Kiểm tra nếu trạng thái mới không nằm trong danh sách cho phép
        if (!in_array($newStatus, $allowedTransitions[$currentStatus] ?? [])) {
            return back()->withErrors([
                'status' => "Không được phép chuyển trạng thái không hợp lệ."
            ]);
        }

        // Cập nhật
        $contact->update([
            'status' => $newStatus,
            'handled_by' => auth()->id(),
            'responded_at' => in_array($newStatus, ['responded', 'rejected']) ? now() : null,
        ]);
        // dd($contact->toArray());

        return redirect()->back()->with('success', 'Trạng thái đã được cập nhật thành công.');
    }
}
