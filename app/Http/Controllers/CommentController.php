<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Topic;
use App\Comment;
use App\Notifications\NewCommentPosted;

class CommentController extends Controller
{
    
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function store(Topic $topic)
    {
    	request()->validate([
    		'content' => 'required|min:3',
    	]);

    	$comment = new Comment;
    	$comment->content = request('content');
    	$comment->user_id = auth()->user()->id;

    	$topic->comments()->save($comment);

    	//Notification 
    	$topic->user->notify(new NewCommentPosted($topic, auth()->user()));

    	return redirect()->route('topics.show', $topic);
    }

    public function storeReply(Comment $comment)
    {
    	request()->validate([

    		'replyComment' => 'required|min:2',

    	]);

    	$commentReply = new Comment;
    	$commentReply->content = request('replyComment');
    	$commentReply->user_id = auth()->user()->id;
    	$comment->comments()->save($commentReply);

    	return redirect()->back();
    }

    public function markedAsSolution(Topic $topic, Comment $comment)
    {

    	if(auth()->user()->id == $topic->user_id){

    		$topic->solution = $comment->id;
    		$topic->save();

    		return response()->json(['success' => ['Marqué comme solution']], 200);
    	}
    	else{
    		return response()->json(['errors' => ['error' => 'Utilisateur non valide']], 401);
    	}
    }
}
