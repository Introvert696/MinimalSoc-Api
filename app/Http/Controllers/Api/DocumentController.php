<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Requests\Document\StoreRequest;
use Illuminate\Support\Facades\Auth;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return Document::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        //$file->storeAs('/uploads', $fileName);

        $data['title']  = $file->getClientOriginalName();
        $data['path'] = "/image/" . $file->getClientOriginalName();
        $data['creater'] = Auth()->user()->id;
        $document = Document::create($data);
        return $document;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $document = Document::find($id);
        if ($document == null) {
            $data['error']['message'] = 'Not found';
            $data['error']['code'] = 404;
            return $data;
        }
        return Document::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRequest $request, $id)
    {
        $data = $request->validated();
        $data['id'] = $id;
        $data['creater'] = Auth()->user()->id;
        $document = Document::find($id);
        if ($document == null) {
            $error['error']['message'] = 'Not found';
            $error['error']['code'] = 404;
            return $error;
        } else {
            if ($document['creater'] == Auth()->user()->id) {
                $document->update($data);
                return $document;
            } else {
                $error['error']['message'] = 'You are not creater';
                $error['error']['code'] = 405;
                return $error;
            }
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
        return Document::destroy($id);
    }
}
