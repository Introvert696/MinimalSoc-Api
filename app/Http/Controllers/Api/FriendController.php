<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use Illuminate\Http\Request;
use App\Http\Requests\Friend\StoreRequest;
use App\Http\Requests\Friend\UpdateRequest;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\Extension\SmartPunct\EllipsesParser;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth()->user()->id;
        $friends = Friend::where([["friend_status", '=', '1'], ['first_user', '=', $id]])->orWhere([["friend_status", '=', '1'], ['second_user', '=', $id]])->get();

        $frend_request = Friend::where("friend_status", 0)->where("second_user", $id)->get();

        foreach ($friends as $f) {
            $f->firstuser;
            $f->seconduser;
        }
        foreach ($frend_request as $r) {
            $r->firstuser;
            $r->seconduser;
        }
        $response['friend'] = $friends;
        $response['requests'] = $frend_request;
        return $response;
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

        $data['friend_status'] = 0;

        return Friend::firstOrCreate($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $friend = Friend::find($id);
        if ($friend == null) {
            $error['error']['message'] = 'Not Found';
            $error['error']['code'] = 404;
            return $error;
        } else {
            return Friend::find($id);
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
        $friend = Friend::find($id);
        if ($friend == null) {
            $error['error']['message'] = 'Not Found';
            $error['error']['code'] = 404;
            return $error;
        } else {

            $data = $request->validated();

            $friend->update($data);
            return $friend;
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
        $friend = Friend::find($id);
        if ($friend == null) {
            $error['error']['message'] = 'Not Found';
            $error['error']['code'] = 404;
            return $error;
        } else {
            Friend::destroy($id);
            $inf['data']['message'] = 'Delete';
            $inf['data']['code'] = 202;
            return $inf;
        }
    }
}
