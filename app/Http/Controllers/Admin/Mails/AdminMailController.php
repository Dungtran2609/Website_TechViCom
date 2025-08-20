<?php

	namespace App\Http\Controllers\Admin\Mails;

	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Models\MailTemplate;
	use Illuminate\Support\Facades\Mail;
	use App\Mail\DynamicMail;
	use Illuminate\Support\Facades\Blade;
	use App\Models\User;

	class AdminMailController extends Controller
	{
	// Danh sách các mail template (có tìm kiếm)
	public function index(Request $request)
	{
		$query = MailTemplate::orderByDesc('id');
		if ($request->filled('q')) {
			$q = $request->q;
			$query->where(function($sub) use ($q) {
				$sub->where('name', 'like', "%$q%")
					->orWhere('subject', 'like', "%$q%")
					->orWhere('type', 'like', "%$q%")
					;
			});
		}
		$mails = $query->get();
		return view('admin.mails.index', compact('mails'));
	}
	// Route POST: admin/mails/{id}/toggle-auto-send
	public function toggleAutoSend($id)
	{
		$mail = MailTemplate::findOrFail($id);
		$mail->auto_send = !$mail->auto_send;
		$mail->save();
		return back()->with('success', 'Đã cập nhật trạng thái tự động gửi!');
	}

	// Route POST: admin/mails/{id}/send-test
	public function sendTest(Request $request, $id)
	{
		$emails = $request->input('emails');
		$emailsArr = collect(explode(',', $emails))
			->map(fn($e) => trim($e))
			->filter(fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
			->unique()
			->values();
		// TODO: Gửi mail thật ở đây
		if ($emailsArr->isEmpty()) {
			return back()->with('error', 'Vui lòng nhập ít nhất 1 email hợp lệ!');
		}
		return back()->with('success', 'Đã gửi thử email tới: ' . $emailsArr->implode(', '));
	}

	// Form tạo mới
	public function create()
	{
		return view('admin.mails.create');
	}

	// Lưu mail mới
	public function store(Request $request)
	{
		$data = $request->validate([
			'name' => 'required|string|max:255',
			'subject' => 'required|string|max:255',
			'type' => 'nullable|string|max:255',
			'content' => 'required',
			'is_active' => 'nullable|boolean',
			'auto_send' => 'nullable|boolean',
		]);
		$data['is_active'] = $request->has('is_active');
		$data['auto_send'] = $request->has('auto_send');
		MailTemplate::create($data);
		return redirect()->route('admin.mails.index')->with('success', 'Đã thêm mail template!');
	}

	// Form sửa
	public function edit($id)
	{
		$mail = MailTemplate::findOrFail($id);
		return view('admin.mails.edit', compact('mail'));
	}

	// Cập nhật mail
	public function update(Request $request, $id)
	{
		$mail = MailTemplate::findOrFail($id);
		$data = $request->validate([
			'name' => 'required|string|max:255',
			'subject' => 'required|string|max:255',
			'type' => 'nullable|string|max:255',
			'content' => 'required',
			'is_active' => 'nullable|boolean',
			'auto_send' => 'nullable|boolean',
		]);
		$data['is_active'] = $request->has('is_active');
		$data['auto_send'] = $request->has('auto_send');
		$mail->update($data);
		return redirect()->route('admin.mails.index')->with('success', 'Đã cập nhật mail template!');
	}

	// Xóa mềm mail (vào thùng rác)
	public function destroy($id)
	{
		$mail = MailTemplate::findOrFail($id);
		$mail->delete();
		return redirect()->route('admin.mails.index')->with('success', 'Đã chuyển mail vào thùng rác!');
	}

	// Xem chi tiết mail
	public function show($id)
	{
		$mail = MailTemplate::findOrFail($id);
		return view('admin.mails.show', compact('mail'));
	}

	// Danh sách mail trong thùng rác
	public function trash()
	{
		$mails = MailTemplate::onlyTrashed()->orderByDesc('id')->get();
		return view('admin.mails.trash', compact('mails'));
	}

	// Khôi phục mail từ thùng rác
	public function restore($id)
	{
		$mail = MailTemplate::onlyTrashed()->findOrFail($id);
		$mail->restore();
		return redirect()->route('admin.mails.trash')->with('success', 'Đã khôi phục mail!');
	}

	// Xóa vĩnh viễn mail
	public function forceDelete($id)
	{
		$mail = MailTemplate::onlyTrashed()->findOrFail($id);
		$mail->forceDelete();
		return redirect()->route('admin.mails.trash')->with('success', 'Đã xóa vĩnh viễn mail!');
	}

	// Trang gửi mail động (form chọn user/email)
	public function sendForm()
	{
		$mailTemplates = \App\Models\MailTemplate::where('is_active', 1)->get();
		$users = \App\Models\User::where('is_active', 1)->get();
		return view('admin.mails.send', compact('mailTemplates', 'users'));
	}

	// Xử lý gửi mail động (POST)
	public function send(Request $request)
	{
		$request->validate([
			'mail_ids' => 'required|array',
			'mail_ids.*' => 'exists:mail_templates,id',
			'user_ids' => 'nullable|array',
			'user_ids.*' => 'exists:users,id',
			'emails' => 'nullable|string',
			'coupon_code' => 'nullable|string',
		]);
		$mailIds = $request->mail_ids;
		$emailsArr = collect();
		$userMap = [];
		// Lấy email từ user_ids
		if ($request->filled('user_ids')) {
			$users = User::whereIn('id', $request->user_ids)->get();
			foreach ($users as $user) {
				$emailsArr->push($user->email);
				$userMap[$user->email] = $user;
			}
		}
		// Lấy email từ trường nhập tay
		if ($request->filled('emails')) {
			$inputEmails = collect(explode(',', $request->emails))
				->map(fn($e) => trim($e))
				->filter(fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL));
			foreach ($inputEmails as $email) {
				$emailsArr->push($email);
			}
		}
		$emailsArr = $emailsArr->unique()->values();
		if ($emailsArr->isEmpty()) {
			return back()->with('error', 'Vui lòng chọn hoặc nhập ít nhất 1 email hợp lệ!')->withInput();
		}
		$success = 0;
		$fail = 0;
		$couponCode = $request->input('coupon_code', '');
		foreach ($mailIds as $mailId) {
			$mail = MailTemplate::find($mailId);
			if (!$mail) continue;
			foreach ($emailsArr as $email) {
				try {
					$user = $userMap[$email] ?? (object)['name' => 'bạn'];
					$content = Blade::render($mail->content, [
						'user' => $user,
						'coupon_code' => $couponCode,
					]);
					Mail::to($email)->send(new DynamicMail($mail->subject, $content));
					$success++;
				} catch (\Exception $e) {
					$fail++;
				}
			}
		}
		$msg = "Đã gửi $success mail động.";
		if ($fail > 0) $msg .= " Không gửi được $fail mail.";
		return back()->with('success', $msg);
	}
}
