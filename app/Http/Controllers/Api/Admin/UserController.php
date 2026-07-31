<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{

    /**
     * Display all customers
     */
    public function index()
    {

        $users = User::latest()
            ->paginate(10);


        return response()->json($users);

    }



    /**
     * Display single customer
     */
    public function show(User $user)
    {

        return response()->json($user);

    }



    /**
     * Delete customer
     */
    public function destroy(User $user)
    {

        $user->delete();


        return response()->json([

            'message' =>
                'Customer deleted successfully'

        ]);

    }

}