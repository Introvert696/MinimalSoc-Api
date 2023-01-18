<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Requests\Message\UpdateRequest;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return Message::where("from", Auth::user()->id)->orWhere("to", Auth::user()->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['from'] = Auth()->user()->id;
        return Message::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $message = Message::find($id);
        if ($message != null) {
            if (($message['to'] == Auth()->user()->id) or ($message['from'] == Auth()->user()->id)) {
                return $message;
            } else {
                $inf['error']['message'] = 'Dont have premission';
                $inf['error']['code'] = 403;
                return $inf;
            }
        } else {
            $inf['error']['message'] = 'Message not found';
            $inf['error']['code'] = 404;
            return $inf;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $message = Message::find($id);
        if ($message != null) {
            if (($message['to'] == Auth()->user()->id) or ($message['from'] == Auth()->user()->id)) {
                $message->update($request->validated());
                return $message;
            } else {
                $inf['error']['message'] = 'Dont have premission';
                $inf['error']['code'] = 403;
                return $inf;
            }
        } else {
            $inf['error']['message'] = 'Message not found';
            $inf['error']['code'] = 404;
            return $inf;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = Message::find($id);
        if ($message != null) {
            if (($message['to'] == Auth()->user()->id) or ($message['from'] == Auth()->user()->id)) {
                Message::destroy($id);
                $inf['data']['message'] = 'Message delete';
                $inf['data']['code'] = 202;
                return $inf;
            } else {
                $inf['error']['message'] = 'Dont have premission';
                $inf['error']['code'] = 403;
                return $inf;
            }
        } else {
            $inf['error']['message'] = 'Message not found';
            $inf['error']['code'] = 404;
            return $inf;
        }
    }
}
