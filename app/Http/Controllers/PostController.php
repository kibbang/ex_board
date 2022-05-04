<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use Carbon\Carbon;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Post::all();
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
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'title' => 'required|min:3',
            'body' => 'required',
        ]);

        $userId = Auth::id();

        Post::create([
            'user_id' => $userId,
            'title' => $request->title,
            'body' => $request->body
        ]);

        return response()->json(['message' => 'Successfully added']);
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
        $post = Post::where('id', $id)->first();

        return $post;
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
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $post = Post::findOrFail($id);

//        $data = $request->all();
//
//        $post->fill($data)->save();
        $post->update([
            'title'=>$request->title,
            'body'=>$request->body,
            'updated_at'=>Carbon::now()
        ]);

        return response()->json(['message'=>'Successfuly updated']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Post::where('id', $id)->delete();

        return response()->json(['message' => 'Successfully deleted']);
    }
}
