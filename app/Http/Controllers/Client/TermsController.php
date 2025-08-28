<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class TermsController extends Controller
{
    /**
     * Hiển thị trang điều khoản và điều kiện
     */
    public function index()
    {
        return view('client.pages.terms');
    }
}
