<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Protected for Admin|Librarian at route level
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    public function storeLibrarian(Request $request)
    {
        // Protected for Admin only at route level
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('Librarian');

        return redirect()->back()->with('success', 'Librarian account created successfully.');
    }
}
