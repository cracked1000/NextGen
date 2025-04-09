<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    public function showSignupForm()
    {
        return view('signup');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'role' => 'required|in:customer,seller',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $userData = [
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'status' => 'active',
        ];

        $user = User::create($userData);
        \Auth::login($user);

        // Check if a redirect parameter is present in the request
        if ($request->has('redirect')) {
            return redirect()->to($request->input('redirect'))
                ->with('success', 'Account created successfully!');
        }

        // Fallback to role-based redirect if no redirect parameter
        return redirect()->route($validatedData['role'] === 'customer' ? 'index' : 'sellers.dashboard')
            ->with('success', 'Account created successfully!');
    }
}