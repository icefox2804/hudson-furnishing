<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        // Search by name, email, or phone
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by creation date from
        if ($request->has('created_from') && $request->created_from != '') {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        // Filter by creation date to
        if ($request->has('created_to') && $request->created_to != '') {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        $query->orderBy('created_at', 'desc');
        $contacts = $query->paginate(15)->withQueryString();
        
        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'status' => 'required|in:new,read,replied',
            'notes'  => 'nullable|string|max:1000',
        ],[
            'status.required' => 'Vui nhập trang thái',
            'status.in'       => 'Trang thái khóng hợp lệ',
        ]);

        $contact->update([
            'status' => $request->status,
            'notes'  => $request->notes,
        ]);

        return redirect()->route('admin.contacts.index')->with('success', 'Contact updated successfully!');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Contact deleted successfully!');
    }

    public function markAsRead(Contact $contact)
    {
        $contact->update(['status' => 'read']);
        return redirect()->route('admin.contacts.index')->with('success', 'Contact marked as read!');
    }

    public function markAsReplied(Contact $contact)
    {
        $contact->update(['status' => 'replied']);
        return redirect()->route('admin.contacts.index')->with('success', 'Contact marked as replied!');
    }
}
