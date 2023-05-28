<?php

namespace App\Http\Controllers\Feed;

use App\Models\Feed;
use App\Models\Like;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PostRequest;
use App\Models\Comment;

class FeedController extends Controller
{
    public function fetch()
    {
        $feeds = Feed::with('user')->latest()->get();
        return Response([
            'feeds' => $feeds
        ],200);
    }


    public function store(PostRequest $request){
        $request->validated();

        auth()->user()->feeds()->create([
            'content' => $request->content
        ]);

        return Response([
            'message' => 'success !!!!!',201
        ]);
    }

    public function likePost($feed_id)
    {
        $feed = Feed::whereId($feed_id)->first();

        if(!$feed){
            return Response(['message'=>'404 NOT FOUND'],404);
        }

        // UNLIKE POST
        $unlike_post = Like::where('user_id',auth()->id())->where('feed_id',$feed_id)->delete();
        if($unlike_post){
            return Response(['mesage' => 'Unliked'],200);
        }
        
        // LIKE POST
        $like_post = Like::create([
            'user_id' => auth()->id(),
            'feed_id' => $feed_id
        ]);
        if($like_post){
            return Response(['mesage' => 'Liked'],201);
        }else{
            return Response(['message' => 'Error']);
        }

    }

    public function comment(CommentRequest $request ,$feed_id)
    {
        $request->validated();

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'feed_id' => $feed_id,
            'body' =>   $request->body
        ]);

        return response([
            'message' => 'success  !!!'
        ],201);
    }

    public function getComment($feed_id)
    {
        $comments = Comment::where('feed_id',$feed_id)->with('feed','user')->latest()->get();

        return response([
            'comments' => $comments
        ],200);
    }
}
