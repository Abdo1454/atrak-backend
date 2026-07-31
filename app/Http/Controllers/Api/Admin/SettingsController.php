<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;


class SettingsController extends Controller
{

    /**
     * Get store settings
     */
    public function show()
    {
        $settings = Setting::first();


        return response()->json([
            'settings' => $settings
        ]);
    }



    /**
     * Update store settings
     */
    public function update(Request $request)
    {

        $validated = $request->validate([

            'store_name' =>
                'nullable|string|max:255',

            'email' =>
                'nullable|email',

            'phone' =>
                'nullable|string|max:50',

            'address' =>
                'nullable|string',

            'logo' =>
                'nullable|string',

        ]);



        $settings = Setting::first();



        if ($settings) {

            $settings->update($validated);

        } else {

            $settings =
                Setting::create($validated);

        }



        return response()->json([

            'message' =>
                'Settings updated successfully',

            'settings' =>
                $settings

        ]);

    }

}