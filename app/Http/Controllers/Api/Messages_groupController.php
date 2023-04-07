<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messages_group\StoreRequest;
use App\Http\Requests\Messages_group\UpdateRequest;
use App\Models\Message;
use App\Models\Messages_group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Messages_groupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages_group = Messages_group::where("first_user", Auth()->user()->id)->orWhere("second_user", Auth()->user()->id)->get();
        foreach ($messages_group as $m) {
            $m->messages;
            $m->firstuser;
            $m->seconduser;
        };
        return $messages_group;
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
        $data['first_user'] = Auth()->user()->id;
        return Messages_group::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $message_group = Messages_group::find($id);
        // $current_mess['group'] = $message_group;
        // if (($message_group['first_user'] == Auth()->user()->id) or ($message_group['second_user'] == Auth()->user()->id)) {
        //     $messages_group_message = Messages_group::find($id)->messages()->get();
        //     $current_mess['message'] = $messages_group_message;

        //     return $current_mess;
        // } else {
        //     $inf['error']['message'] = 'not found';
        //     $inf['error']['code'] = 404;
        //     return $inf;
        // }
        $message_group = Messages_group::find($id);
        $message_group->firstuser;
        $message_group->seconduser;
        $message_group->messages;

        return ($message_group);
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
        $data = $request->validated();
        $message_group = Messages_group::find($id);
        if ($message_group != null) {
            if (($message_group['first_user'] == Auth()->user()->id) or ($message_group['second_user'] == Auth()->user()->id)) {
                $message_group->update($data);
                return $message_group;
            } else {
                $inf['error']['message'] = 'not found';
                $inf['error']['code'] = 404;
                return $inf;
            }
        } else {
            $inf['error']['message'] = 'not found';
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

        $message_group = Messages_group::find($id);
        if ($message_group != null) {
            if (($message_group['first_user'] == Auth()->user()->id) or ($message_group['second_user'] == Auth()->user()->id)) {
                Messages_group::destroy($id);
                $inf['data']['message'] = 'delete';
                $inf['data']['code'] = 202;
                return $inf;
            } else {
                $inf['error']['message'] = 'not found';
                $inf['error']['code'] = 404;
                return $inf;
            }
        } else {
            $inf['error']['message'] = 'not found';
            $inf['error']['code'] = 404;
            return $inf;
        }
    }
}
