<?php

namespace App\Observers;

use App\Models\User;
use App\Models\MailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\DynamicMail;
use Illuminate\Support\Facades\Blade;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user)
    {
        // Tìm mail template chào mừng đang active
        $mail = MailTemplate::where('type', 'welcome')
            ->where('is_active', 1)
            ->first();
        if ($mail && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            $content = Blade::render($mail->content, [
                'user' => $user,
            ]);
            try {
                Mail::to($user->email)->send(new DynamicMail($mail->subject, $content));
            } catch (\Exception $e) {
                // Log hoặc bỏ qua lỗi gửi mail
            }
        }
    }
}
