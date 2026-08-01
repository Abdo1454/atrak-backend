<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    /**
     * Handle perfume assistant chat
     */
    public function send(Request $request)
    {

        $validated = $request->validate([

            'message' => [
                'required',
                'string',
                'max:500'
            ]

        ]);



        $message = mb_strtolower(
            $validated['message']
        );



        $reply = "";



        /*
        |--------------------------------------------------------------------------
        | Oriental Perfumes
        |--------------------------------------------------------------------------
        */


        if (
            str_contains($message, 'شرقي') ||
            str_contains($message, 'عود') ||
            str_contains($message, 'عنبر') ||
            str_contains($message, 'خشبي')
        ) {


            $products = Product::where('description', 'like', '%عود%')
                ->orWhere('description', 'like', '%شرقي%')
                ->orWhere('description', 'like', '%عنبر%')
                ->limit(3)
                ->get();



            if ($products->count()) {

                $reply = "أنصحك بهذه العطور الشرقية 🌙:\n\n";


                foreach ($products as $product) {

                    $reply .=
                        "• {$product->name} - {$product->price} جنيه\n";

                }

            } else {

                $reply =
                    "لدينا عطور شرقية مميزة بروائح العود والعنبر 🌙";

            }

        }



        /*
        |--------------------------------------------------------------------------
        | Fresh Perfumes
        |--------------------------------------------------------------------------
        */


        elseif (
            str_contains($message, 'منعش') ||
            str_contains($message, 'صيف') ||
            str_contains($message, 'حمضي') ||
            str_contains($message, 'fresh')
        ) {


            $products = Product::where('description', 'like', '%منعش%')
                ->orWhere('description', 'like', '%fresh%')
                ->orWhere('description', 'like', '%حمضي%')
                ->limit(3)
                ->get();



            if ($products->count()) {

                $reply =
                    "هذه عطور منعشة مناسبة لك 🌊:\n\n";


                foreach ($products as $product) {

                    $reply .=
                        "• {$product->name} - {$product->price} جنيه\n";

                }

            } else {

                $reply =
                    "أنصحك بالعطور البحرية والحمضية للأجواء الصيفية 🌊";

            }

        }



        /*
        |--------------------------------------------------------------------------
        | Luxury Perfumes
        |--------------------------------------------------------------------------
        */


        elseif (
            str_contains($message, 'فاخر') ||
            str_contains($message, 'فخم') ||
            str_contains($message, 'luxury')
        ) {


            $products = Product::orderBy(
                'price',
                'desc'
            )
            ->limit(3)
            ->get();



            $reply =
                "اختيارات فاخرة لك ✨:\n\n";



            foreach ($products as $product) {

                $reply .=
                    "• {$product->name} - {$product->price} جنيه\n";

            }

        }



        /*
        |--------------------------------------------------------------------------
        | Default Response
        |--------------------------------------------------------------------------
        */


        else {


            $reply =
                "أهلاً بك في Atark 🌸\n\n".
                "يمكنني مساعدتك في اختيار العطر المناسب.\n\n".
                "جرب أن تسألني:\n".
                "- أريد عطراً شرقياً بالعود\n".
                "- أريد عطراً منعشاً للصيف\n".
                "- أبحث عن عطر فاخر";

        }



        return response()->json([

            'reply' => $reply

        ]);

    }

}