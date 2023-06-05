<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group_post\StoreRequest;
use App\Models\Group;
use App\Models\Group_post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Group_postController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Group_post::all();
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
        $group = Group::find($data['creater']);
        if ($group != null) {

            if ($group->creater == Auth()->user()->id) {
                $group_post = Group_post::create($data);
                return $group_post;
            } else {
                $inf['error']['message'] = 'You are not creater this group';
                $inf['error']['code'] = 403;
                return $inf;
            }
        } else {
            $inf['error']['message'] = 'Not found';
            $inf['error']['code'] = 404;
            return $inf;
        }
        //dd($group);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group_post = Group_post::find($id);
        if ($group_post != null) {
            return $group_post;
        } else {
            $inf['error']['message'] = 'Not found';
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
    public function update(Request $request, $id)
    {
        $data = $request->validated();
        $group = Group::find($data['creater']);
        if ($group != null) {
            if ($group->creater == Auth()->user()->id) {
                $group_post = Group_post::find($id);
                $group_post->update($data);
                return $group_post;
            } else {
                $inf['error']['message'] = 'You are not creater this group';
                $inf['error']['code'] = 403;
                return $inf;
            }
        } else {
            $inf['error']['message'] = 'Not found';
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
        $group_post = Group_post::find($id);
        if ($group_post != null) {
            $group = Group::find($group_post->creater);

            dd($group);
            if ($group != null) {
                if ($group->creater == Auth()->user()->id) {

                    Group_post::destroy($id);

                    $inf['error']['message'] = 'Group post delete';
                    $inf['error']['code'] = 202;
                    return $inf;
                    //return $group_post;
                } else {
                    $inf['error']['message'] = 'You are not creater this group';
                    $inf['error']['code'] = 403;
                    return $inf;
                }
            } else {
                $inf['error']['message'] = 'Not found';
                $inf['error']['code'] = 404;
                return $inf;
            }
        } else {
            $inf['error']['message'] = 'Not found';
            $inf['error']['code'] = 404;
            return $inf;
        }
    }
}
