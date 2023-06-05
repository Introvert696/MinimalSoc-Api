<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscribe_to_group\StoreRequest;
use App\Models\Subscribe_to_group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Subscribe_to_groupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subsc = Subscribe_to_group::where("user_id", Auth()->user()->id)->get();
        foreach ($subsc as $s) {
            $s->group;
        }
        return $subsc;
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
        $data['user_id'] = Auth()->user()->id;
        return Subscribe_to_group::firstOrCreate($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Subscribe_to_group::find($id);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sbc = Subscribe_to_group::find($id);
        if ($sbc != null) {
            if ($sbc['user_id'] == Auth()->user()->id) {
                Subscribe_to_group::destroy($id);
                $inf['data']['message'] = 'Delete';
                $inf['data']['code'] = 202;
                return response($inf, 202);
            } else {
                $inf['error']['message'] = 'not creater';
                $inf['error']['code'] = 403;
                return response($inf, 403);
            }
        } else {
            $inf['error']['message'] = 'not found';
            $inf['error']['code'] = 404;
            return response($inf, 404);
        }
    }
}
