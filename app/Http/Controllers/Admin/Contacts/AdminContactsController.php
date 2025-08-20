<?php

namespace App\Http\Controllers\Admin\Contacts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\User;

class AdminContactsController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::with(['user', 'handledByUser']);

        // Tìm kiếm theo từ khóa
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%")
                    ->orWhere('phone', 'like', "%$keyword%")
                    ->orWhere('subject', 'like', "%$keyword%")
                    ->orWhere('message', 'like', "%$keyword%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Lọc theo ngày gửi
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Lọc theo người xử lý
        if ($request->filled('handled_by')) {
            $query->where('handled_by', $request->handled_by);
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'name') {
            $query->orderBy('name', $sortOrder);
        } elseif ($sortBy === 'status') {
            $query->orderBy('status', $sortOrder);
        } elseif ($sortBy === 'updated_at') {
            $query->orderBy('updated_at', $sortOrder);
        } else {
            $query->orderBy('created_at', $sortOrder);
        }

        $contacts = $query->paginate(15);

        // Lấy danh sách người xử lý cho filter
        $handlers = \App\Models\User::whereIn('id', Contact::whereNotNull('handled_by')->pluck('handled_by'))
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.contacts.index', compact('contacts', 'handlers'));
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
        
        // Chỉ cho phép xóa liên hệ có trạng thái 'responded' hoặc 'rejected'
        if (!in_array($contact->status, ['responded', 'rejected'])) {
            return redirect()->route('admin.contacts.index')
                ->with('error', 'Chỉ có thể xóa liên hệ đã được phản hồi hoặc bị từ chối.');
        }
        
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
            'handled_by' => auth()->user()->id,
            'responded_at' => in_array($newStatus, ['responded', 'rejected']) ? now() : null,
        ]);
        // dd($contact->toArray());

        return redirect()->back()->with('success', 'Trạng thái đã được cập nhật thành công.');
    }
}
