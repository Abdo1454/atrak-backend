<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserDashboardController extends Controller
{
    /**
     * Dashboard
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Order::where('user_id', $user->id)
            ->latest()
            ->with('items')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,

            'user' => $user,

            'stats' => [
                'total_orders' => Order::where('user_id', $user->id)->count(),

                'pending_orders' => Order::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->count(),

                'completed_orders' => Order::where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->count(),

                // سيتم استبدالها لاحقًا عند إنشاء جدول Favorites
                'favorites' => 0,
            ],

            'orders' => $orders,
        ]);
    }

    /**
     * Profile
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ]);
    }

    /**
     * Update Profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user' => $user,
        ]);
    }

    /**
     * Change Password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => [
                'required',
            ],

            'password' => [
                'required',
                'confirmed',
                'min:6',
            ],
        ]);

        $user = $request->user();

        if (!Hash::check(
            $validated['current_password'],
            $user->password
        )) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
        ]);
    }

    /**
     * User Orders
     */
    public function orders(Request $request)
    {
        $orders = Order::where(
            'user_id',
            $request->user()->id
        )
            ->latest()
            ->with('items')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }

    /**
     * User Favorites
     */
    public function favorites(Request $request)
    {
        return response()->json([
            'success' => true,
            'favorites' => [],
            'message' => 'Favorites feature is not implemented yet.',
        ]);
    }
}