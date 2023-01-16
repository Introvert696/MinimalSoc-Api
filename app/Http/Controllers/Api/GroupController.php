<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\StoreRequest;
use App\Http\Requests\Group\UpdateRequest;
use App\Models\Group;
use Exception;
use Illuminate\Http\Request;

use function GuzzleHttp\Promise\exception_for;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Group::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $date = $request->validated();
        $date['creater'] = Auth()->user()->id;
        return Group::create($date);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Group::find($id);
        if ($group != null) {
            return $group;
        } else {
            $inf['error']['message'] = 'Group not found';
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
        $group = Group::find($id);
        if ($group != null) {
            if ($group->creater == Auth()->user()->id) {
                $date = $request->validated();
                $group->update($date);
                return $group;
            } else {
                $inf['error']['message'] = 'You are not creater a group';
                $inf['error']['code'] = 403;
                return $inf;
            }
        } else {
            $inf['error']['message'] = 'Group not found';
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
        $group = Group::find($id);
        if ($group != null) {
            if ($group->creater == Auth()->user()->id) {
                try {
                    Group::destroy($id);
                    $inf['data']['message'] = 'Group has been delete';
                    $inf['data']['code'] = 202;
                    return $inf;
                } catch (Exception $ex) {
                    $inf['error']['message'] = 'Group have a posts';
                    $inf['error']['code'] = 403;
                    return $inf;
                }
            }
        } else {
            $inf['error']['message'] = 'Group not found';
            $inf['error']['code'] = 404;
            return $inf;
        }
    }
}
