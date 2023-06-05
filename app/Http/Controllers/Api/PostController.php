<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Friend;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $friend_list = [];
        $raw_posts = [];
        $posts = [];
        //получение друзей пользователя
        $friends = Friend::where("first_user", Auth()->user()->id)->orWhere("second_user", Auth()->user()->id)->get();
        foreach ($friends as $f) {
            if ($f['first_user'] == Auth()->user()->id) {
                array_push($friend_list, $f['second_user']);
            } else {
                array_push($friend_list, $f['first_user']);
            }
        }
        //получение постов самого пользователя
        $user_post = Post::where("creater", Auth()->user()->id)->get();

        foreach ($user_post as $up) {
            array_push($raw_posts, $up);
        }

        //получение постов друзей пользователя
        foreach ($friend_list as $fl) {
            $fr_posts = Post::where("creater", $fl)->get();
            foreach ($fr_posts as $fp) {
                array_push($raw_posts, $fp);
            }
        }

        //Добавление постам, строк о создателях
        foreach ($raw_posts as $post) {
            $current_post["post"] = $post;
            $post->creator;
            array_push($posts, $current_post);
        }

        //array_reverse($posts);
        return $posts;
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
        $data['creater'] = Auth()->user()->id;
        return Post::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Post::findOrFail($id);
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
        $post = Post::find($id);
        if ($post != null) {
            if ($post['creater'] == Auth()->user()->id) {
                $post->update($data);
                return $post;
            } else {
                $inf['error']['message'] = 'not creater';
                $inf['error']['code'] = 403;
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
        $post = Post::find($id);
        if ($post != null) {
            if ($post['creater'] == Auth()->user()->id) {
                Post::destroy($id);
                $inf['data']['message'] = 'delete';
                $inf['data']['code'] = 202;
                return $inf;
            } else {
                $inf['error']['message'] = 'not creater';
                $inf['error']['code'] = 403;
                return $inf;
            }
        } else {
            $inf['error']['message'] = 'not found';
            $inf['error']['code'] = 404;
            return $inf;
        }
    }
}
