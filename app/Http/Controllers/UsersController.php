<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index()
    {
        $users = Cache::remember('users', 5, function () {
            return User::all();
        });

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:100',
            'cellphone' => 'required|max:25',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message'   => 'Validation Failed',
                'errors'    => $validator->errors()->all()
            ], 422);
        }

        $user = new User();
        $user->fill($data);
        $user->save();

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        $userId = $user->getAttribute('id');
        $data = $request->all();

        if (!$userId) {
            return response()->json([
                'error' => 'record_not_found'
            ], 404);
        }

        $user->fill($data);
        $user->save();

        return response()->json($user);
    }

    public function show(User $user)
    {
        $userId = $user->getAttribute('id');

        if (!$userId) {
            return response()->json([
                'error' => 'record_not_found'
            ], 404);
        }

        return response()->json($user);
    }

    public function destroy(Request $request, User $user)
    {
        $userId = $user->getAttribute('id');

        if (!$userId) {
            return response()->json([
                'error' => 'record_not_found'
            ], 404);
        }

        $user->delete();
    }
}

