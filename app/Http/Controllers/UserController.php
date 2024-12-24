<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UserController {
    public function update(Request $request, $id) {
        $user = User::find($id);

        if(!$user) {
            return response()->json([
                'message' => 'User not found'
            ],400);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password'=>'required|min:6|string',
        ]);
        $validatedData['password'] = bcrypt($validatedData['password']);

        $user->update($validatedData);
        return response()->json([ 'message' => 'User updated successfully', 'user' => $user]);
    }
    public function delete($id) {
        $user = User::find($id);

        if(!$user) {
            return response()->json([
                'message' => 'User not found'
            ],400);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function get($id) {
        $user = User::find($id);
        if(!$user) {
            return response()->json(['message' => 'User not found'], 400);
        }
        return response()->json(['user' => $user]);
    }
}
