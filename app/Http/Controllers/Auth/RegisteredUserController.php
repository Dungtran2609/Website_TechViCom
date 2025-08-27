<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'],
                'password_confirmation' => ['required'],
                'terms' => ['required', 'accepted'],
            ], [
                'name.required' => 'Vui lòng nhập họ và tên',
                'name.string' => 'Họ và tên phải là chuỗi ký tự',
                'name.max' => 'Họ và tên không được quá 255 ký tự',
                'email.required' => 'Vui lòng nhập địa chỉ email',
                'email.email' => 'Địa chỉ email không hợp lệ',
                'email.unique' => 'Email này đã được sử dụng',
                'password.required' => 'Vui lòng nhập mật khẩu',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt',
                'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu',
                'terms.required' => 'Bạn phải đồng ý với điều khoản sử dụng',
                'terms.accepted' => 'Bạn phải đồng ý với điều khoản sử dụng',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Gán role user cho tài khoản vừa tạo
            $roleUser = Role::where('name', 'user')->first();

            if ($roleUser) {
                $user->roles()->attach($roleUser->id);
            }

            event(new Registered($user));

            // Tự động đăng nhập sau khi đăng ký
            Auth::login($user);

            // Check if it's an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng ký tài khoản thành công! Chào mừng bạn đến với TechViCom.'
                ]);
            }

            return redirect()->route('home')->with('status', 'Đăng ký tài khoản thành công! Chào mừng bạn đến với TechViCom.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Check if it's an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $e->errors()
                ], 422);
            }

            throw $e;
        } catch (\Exception $e) {
            // Check if it's an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra, vui lòng thử lại'
                ], 500);
            }

            throw $e;
        }
    }
}
