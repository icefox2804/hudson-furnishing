<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\SiteSetting;

class ContactController extends Controller
{
    /**
     * Display the contact form.
     */
    public function index()
    {
        $siteSettings = SiteSetting::pluck('value', 'key')->toArray();
        
        return view('pages.contact.index', compact('siteSettings'));
    }

    /**
     * Store a newly created contact message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'message' => 'required|string|max:1000',
        ], [
            'name.required'    => 'Vui lòng nhập họ tên.',
            'email.required'   => 'Vui lòng nhập địa chỉ email hợp lệ.',
            'phone.required'   => 'Vui lòng nhập số điện thoại.',
            'message.required' => 'Vui lòng nhập thông điệp của bạn.',
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'status' => 'new',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your message. We will get back to you soon!'
        ]);
    }
}
