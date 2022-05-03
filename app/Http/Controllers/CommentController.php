<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use Carbon\Carbon;

class CommentController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth:api')->except('index','show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($post_id)
    {
        //
        $commentList = Comment::where('post_id', $post_id)->get();
        return $commentList;
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
     * @param  int $post_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $post_id)
    {
        //
        $this->validate($request, [
            'comment' => 'required',
        ]);

        $userId = Auth::id();
        Comment::create([
            'post_id' => $post_id,
            'user_id' => $userId,
            'comment' => $request->comment
        ]);

        return response()->json(['message' => 'Comment was added successfuly']);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            'comment' => 'required',
        ]);

        $comment = Comment::findOrFail($id);

        $comment->update([
            'comment' => $request->comment,
            'updated_at' => Carbon::now()
        ]);

        return response()->json(['message'=>'Comment was successfuly updated']);
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
        Comment::where('id', $id)->delete();

        return response()->json(['message' => 'Comment was uccessfully deleted']);
    }
}
