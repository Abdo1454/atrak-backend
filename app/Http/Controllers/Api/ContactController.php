<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Store a newly created contact message.
     */
    public function store(StoreContactRequest $request)
    {
        $contact = Contact::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
            'data' => $contact,
        ], 201);
    }
}