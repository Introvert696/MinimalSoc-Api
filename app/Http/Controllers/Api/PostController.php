<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $raw_posts = Post::all();
        $posts = [];
        foreach ($raw_posts as $post) {
            $current_post["post"] = $post;
            $post->creator;
            array_push($posts, $current_post);
        }
        array_reverse($posts);
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
