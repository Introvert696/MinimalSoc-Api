<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\StoreRequest;
use App\Http\Requests\Group\UpdateRequest;
use App\Models\Document;
use App\Models\Group;
use App\Models\Subscribe_to_group;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $user_photo = $request->file('photo');
        $user_photoName = $user_photo->getClientOriginalName();
        //Загружаем аватар на сервер и добавляем в таблицу документов
        $user_photo->storeAs('/uploads', $user_photoName);
        $photoData['title']  = $user_photo->getClientOriginalName();
        $photoData['path'] = "/image/" . $user_photo->getClientOriginalName();
        $photoData['creater'] = Auth()->user()->id;
        $document = Document::create($photoData);


        $date['photo'] =  $user_photoName;
        //создаем запись в таблице групп
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
            $group->posts;
            $subsribed = Subscribe_to_group::where("user_id", Auth()->user()->id)->where("group_id", $group->id)->get();
            if (isset($subsribed[0]->user_id)) {
                $group["is_sub"] = True;
            } else {
                $group["is_sub"] = False;
            }

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
    public function search($prompt)
    {
        $groups = Group::where('title', 'LIKE', "%{$prompt}%")->get();
        return $groups;
    }
}
