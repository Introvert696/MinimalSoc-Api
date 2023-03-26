<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function show($image)
    {

        $document = Document::where('title', $image)->first();

        if ($document != null) {
            $imagetitle = $document->title;
            $pathToFile = storage_path("app\uploads\\" . $imagetitle);

            if (file_exists($pathToFile)) {
                return response()->file($pathToFile);
            } else {
                $inf['error']['message'] = 'Image not found';
                $inf['error']['code'] = 404;
                return response($inf, 404);
            }
        } else {
            $inf['error']['message'] = 'Image not found';
            $inf['error']['code'] = 404;
            return response($inf, 404);
        }



        // $message = Message::find($id);
        // if ($message != null) {
        //     if (($message['to'] == Auth()->user()->id) or ($message['from'] == Auth()->user()->id)) {
        //         return $message;
        //     } else {
        //         $inf['error']['message'] = 'Dont have premission';
        //         $inf['error']['code'] = 403;
        //         return $inf;
        //     }
        // } else {
        //     $inf['error']['message'] = 'Message not found';
        //     $inf['error']['code'] = 404;
        //     return $inf;
        // }
    }
}
