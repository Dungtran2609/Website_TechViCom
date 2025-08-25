<?php

namespace App\Http\Controllers\Admin\Contacts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Hiển thị danh sách liên hệ đã bị xóa mềm
     */
    public function trashed(Request $request)
    {
        $query = Contact::onlyTrashed()->with(['user', 'handledByUser']);

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

        // Lọc theo người xử lý
        if ($request->filled('handled_by')) {
            $query->where('handled_by', $request->handled_by);
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'deleted_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'name') {
            $query->orderBy('name', $sortOrder);
        } elseif ($sortBy === 'status') {
            $query->orderBy('status', $sortOrder);
        } elseif ($sortBy === 'deleted_at') {
            $query->orderBy('deleted_at', $sortOrder);
        } else {
            $query->orderBy('created_at', $sortOrder);
        }

        $contacts = $query->paginate(15);

        // Lấy danh sách người xử lý cho filter
        $handlers = \App\Models\User::whereIn('id', Contact::withTrashed()->whereNotNull('handled_by')->pluck('handled_by'))
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.contacts.trashed', compact('contacts', 'handlers'));
    }

    public function show($id)
    {
        $contact = Contact::with('handledByUser')->findOrFail($id);

        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Hiển thị chi tiết liên hệ đã bị xóa mềm
     */
    public function showTrashed($id)
    {
        $contact = Contact::onlyTrashed()->with('handledByUser')->findOrFail($id);
        return view('admin.contacts.show-trashed', compact('contact'));
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        
        // Kiểm tra các điều kiện xóa (để đảm bảo an toàn ở backend)
        $errorMessage = $this->checkDeleteConditions($contact);
        if ($errorMessage) {
            return redirect()->route('admin.contacts.index')
                ->with('error', $errorMessage);
        }
        
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Đã xoá liên hệ thành công.');
    }

    /**
     * Kiểm tra các điều kiện trước khi xóa liên hệ
     */
    private function checkDeleteConditions($contact)
    {
        if (!$contact->canBeDeleted()) {
            $reason = $contact->getDeleteRestrictionReason();
            return "Không thể xóa liên hệ \"{$contact->subject}\" vì {$reason}. Vui lòng xử lý trước khi xóa.";
        }
        
        return null; // Không có lỗi, có thể xóa
    }

    /**
     * Xóa vĩnh viễn liên hệ đã bị xóa mềm
     */
    public function forceDelete($id)
    {
        $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->forceDelete();

        return redirect()->route('admin.contacts.trashed')
            ->with('success', 'Đã xóa vĩnh viễn liên hệ thành công.');
    }

    /**
     * Khôi phục liên hệ đã bị xóa mềm
     */
    public function restore($id)
    {
        $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->restore();

        return redirect()->route('admin.contacts.trashed')
            ->with('success', 'Đã khôi phục liên hệ thành công.');
    }

    /**
     * Xóa vĩnh viễn nhiều liên hệ
     */
    public function forceDeleteMultiple(Request $request)
    {
        $request->validate([
            'contact_ids' => 'required|string'
        ]);

        $contactIds = json_decode($request->input('contact_ids'), true);
        if (!is_array($contactIds)) {
            return redirect()->route('admin.contacts.trashed')
                ->with('error', 'Dữ liệu không hợp lệ.');
        }

        $deletedCount = Contact::onlyTrashed()->whereIn('id', $contactIds)->forceDelete();

        return redirect()->route('admin.contacts.trashed')
            ->with('success', "Đã xóa vĩnh viễn {$deletedCount} liên hệ thành công.");
    }

    /**
     * Khôi phục nhiều liên hệ
     */
    public function restoreMultiple(Request $request)
    {
        $request->validate([
            'contact_ids' => 'required|string'
        ]);

        $contactIds = json_decode($request->input('contact_ids'), true);
        if (!is_array($contactIds)) {
            return redirect()->route('admin.contacts.trashed')
                ->with('error', 'Dữ liệu không hợp lệ.');
        }

        $restoredCount = Contact::onlyTrashed()->whereIn('id', $contactIds)->restore();

        return redirect()->route('admin.contacts.trashed')
            ->with('success', "Đã khôi phục {$restoredCount} liên hệ thành công.");
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
            'handled_by' => Auth::id(),
            'responded_at' => in_array($newStatus, ['responded', 'rejected']) ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Trạng thái đã được cập nhật thành công.');
    }
}
