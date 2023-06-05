<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd(111);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [];
        $user = new UserResource(User::find($id));
        if ($user->resource == null) {
            $errorresp['code'] = 404;
            $errorresp['message'] = "User not found";
            return response($errorresp, 404);
        } else {
            $posts = $user->posts;
            $data['user'] = $user;
            $data['posts'] = $posts;
            return $data;
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
        $current_user = User::find($id);
        if (Auth()->user()->id == $id) {

            $data = $request->validated();
            //dd($data);
            //Загрузка аватарки
            $user_photo = $request->file('user_photo');

            $user_photoName = $user_photo->getClientOriginalName();
            //Загрузка беэшки
            $bg = $request->file('bg_image');
            $bgName = $bg->getClientOriginalName();



            //Загружаем аватар на сервер и добавляем в таблицу документов
            $user_photo->storeAs('/uploads', $user_photoName);
            $photoData['title']  = $user_photo->getClientOriginalName();
            $photoData['path'] = "/image/" . $user_photo->getClientOriginalName();
            $photoData['creater'] = $id;
            $document = Document::create($photoData);


            ////Загружаем БГ на сервер и добавляем в таблицу документов
            $bg->storeAs('/uploads', $bgName);
            $photoData['title']  = $bg->getClientOriginalName();
            $photoData['path'] = "/image/" . $bg->getClientOriginalName();
            $photoData['creater'] = $id;
            $document = Document::create($photoData);

            $updated = User::where("id", $id)->update([
                "name" => $data["name"],
                "lastname" => $data["lastname"],
                "birthday" => $data["birthday"],
                "user_photo" => $user_photoName,
                "bg_image" => $bgName,
                "login" => $data["login"],
                "password" => bcrypt($data["password"]),
            ]);
            $response['response'] = "User update";
            $response['code'] = 200;
            return response($response, 200);
        } else {
            $errorresp['code'] = 404;
            $errorresp['message'] = "User not found";
            return response($errorresp, 404);
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
        //
    }
    public function getByNameLastname($prompt)
    {

        $users = User::where('name', 'LIKE', "%{$prompt}%")->orWhere('lastname', 'LIKE', "%{$prompt}%")->get();
        return $users;
    }
}
