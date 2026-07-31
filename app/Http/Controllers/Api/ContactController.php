<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;


class MessageController extends Controller
{

    /**
     * Display all contact messages
     */
    public function index()
    {
        $messages = Contact::latest()
            ->paginate(10);


        return response()->json($messages);
    }



    /**
     * Display single message
     */
    public function show(Contact $message)
    {
        return response()->json($message);
    }



    /**
     * Delete message
     */
    public function destroy(Contact $message)
    {

        $message->delete();


        return response()->json([

            'success' => true,

            'message' =>
                'Message deleted successfully'

        ]);

    }

}