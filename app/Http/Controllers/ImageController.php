<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageData;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,svg|max:10240',
        ]);
        $path = 'storage/app/image/' . date('Y') . '/' . date('n') . '/' . date('d');
        $imageName =  date('Y-m-d') . '_' . Str::random(10) . '.' . $request->file('image')->getClientOriginalName();
        $request->image->storeAs('image', $imageName);
        $imageUrl = asset($path . '/' . $imageName);
        $photo = new ImageData();
        $photo->name = $imageName;
        $photo->path = $path;
        $photo->url = $imageUrl;
        $photo->save();
        return response()->json($photo);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
        $obj_photo = ImageData::findOrFail($id);

        if ($request->hasFile('image')) {
            $imageNewName =  date('Y-m-d') . '_' . Str::random(10) . '.' . $request->file('image')->getClientOriginalName();
            File::delete(public_path($obj_photo->path . "/" . $obj_photo->name));
            $obj_photo->name = $imageNewName;
            $obj_photo->update();
            $request->image->move(public_path($obj_photo->path), $imageNewName);
            return response()->json('update success', $obj_photo);
        }
        return response()->json('update false');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
        $obj_photo = ImageData::findOrFail($id);
        if (File::delete(public_path($obj_photo->path . "/" . $obj_photo->name))) {
            $obj_photo->delete();
            return response()->json('delete success');
        }
        return response()->json('delete false');
    }
}
