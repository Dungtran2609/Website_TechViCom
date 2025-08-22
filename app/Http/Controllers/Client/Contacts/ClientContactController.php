<?php

namespace App\Http\Controllers\Client\Contacts;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientContactController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $todayContactCount = 0;
        $hasReachedLimit = false;
        
        if ($userId) {
            $todayContactCount = Contact::getTodayContactCount($userId);
            $hasReachedLimit = Contact::hasReachedDailyLimit($userId);
        }
        
        return view('client.contacts.index', compact('todayContactCount', 'hasReachedLimit'));
    }

    

    public function store(Request $request)
    {
        // Kiểm tra giới hạn liên hệ
        $userId = Auth::id();
        $email = $request->email;
        
        // Kiểm tra giới hạn cho user đã đăng nhập
        if ($userId && Contact::hasReachedDailyLimit($userId)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Bạn đã đạt giới hạn 5 lần liên hệ trong ngày hôm nay. Vui lòng thử lại vào ngày mai.');
        }
        
        // Kiểm tra giới hạn cho user chưa đăng nhập (theo email)
        if (!$userId && Contact::hasReachedDailyLimit(null, $email)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email này đã đạt giới hạn 5 lần liên hệ trong ngày hôm nay. Vui lòng thử lại vào ngày mai.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|max:255',
            'phone' => ['required', 'string', 'max:20', 'regex:/^(0|\+84)([0-9]{9,10})$/'],
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000'
        ], [
            'name.required' => 'Họ và tên là bắt buộc.',
            'name.string' => 'Họ và tên phải là chuỗi ký tự.',
            'name.max' => 'Họ và tên không được vượt quá :max ký tự.',

            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá :max ký tự.',
            'email.dns' => 'Email phải thuộc một domain hợp lệ.',

            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá :max ký tự.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại Việt Nam (bắt đầu bằng 0 hoặc +84, 10-11 số).',

            'subject.required' => 'Chủ đề là bắt buộc.',
            'subject.string' => 'Chủ đề phải là chuỗi ký tự.',
            'subject.max' => 'Chủ đề không được vượt quá :max ký tự.',

            'message.required' => 'Nội dung tin nhắn là bắt buộc.',
            'message.string' => 'Nội dung tin nhắn phải là chuỗi ký tự.',
            'message.max' => 'Nội dung tin nhắn không được vượt quá :max ký tự.'
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'user_id' => $userId,
            'status' => 'pending',
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Gửi liên hệ thành công! Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }
}
