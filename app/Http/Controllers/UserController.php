<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        // Logic to retrieve and return users
    }
    public function create()
    {
        // Logic to show form for creating a new user
    }
    public function store(Request $request)
    {
        // Logic to validate and store a new user
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'description' => 'nullable|string',
            'department' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }
    public function show($id)
    {
        // Logic to retrieve and return a specific user
    }
    public function edit($id)
    {
        $this->Auth::user()::check()('update', User::class);

        DB::table('users')->where('id', $id)->update([
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'description' => 'Updated description',
            'department' => 'Updated department',
        ]);

    }

    public function update(Request $request, $id)
    {
        // Logic to validate and update a specific user
    }
}
