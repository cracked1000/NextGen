<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SecondHandPart;

class AdminController
{
     public function index()
     {
        $admins = Admin::all();

        dd($admins);  
    
        return view('Admin.index', compact('admins'));
     }
 
     public function create()
     {
         return view('admin.admins.create');
     }
 
     public function store(Request $request)
    {
    $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email|unique:admins',
        'password' => 'required|min:6',
    ]);

    Admin::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'password' => $request->password,  
    ]);

    return redirect()->route('Admin.dashboard');
    }

     public function edit($id)
     {
        $admin = Admin::findOrFail($id);
        return view('Admins.edit', compact('admins'));
     }
 
     public function update(Request $request, $id)
     {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:admins,email,' . $id,
        ]);

        $admin = Admin::findOrFail($id);
        $admin->update($request->all());

        return redirect()->route('Admin.index');
     }
 
     public function dashboard()
    {
        $pendingParts = SecondHandPart::where('status', 'Pending')->with('seller')->get();
        return view('admin.dashboard', compact('pendingParts'));
    }

    public function approve($id)
    {
        $part = SecondHandPart::findOrFail($id);
        $part->update(['status' => 'Available']);
        return redirect()->route('admin.dashboard')->with('success', 'Part approved successfully.');
    }

    public function decline($id)
    {
        $part = SecondHandPart::findOrFail($id);
        $part->update(['status' => 'Declined']);
        return redirect()->route('admin.dashboard')->with('success', 'Part declined successfully.');
    }

    public function addAdmin(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8',
        ]);

        Admin::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Admin added successfully.');
    }
}
