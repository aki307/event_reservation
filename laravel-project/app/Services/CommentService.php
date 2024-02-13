<?php

namespace App\Services;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class CommentService
{
    public function postComment($data, $event_id)
    {
        $user_id = Auth::id();
        $comment = Comment::create([
            'comment' => $data->input('comment'),
            'user_id' => $user_id,
            'event_id' => $event_id,
        ]);

        return ;
    }

    public function getEventById($event_id)
    {
        return Event::findOrFail($event_id);
    }

    public function getById($id) {
       
        $comment = Comment::findOrFail($id);
        $user_id = Auth::id();
        if($comment->user_id !== $user_id) {
            throw new \Exception('コメントの編集は投稿者本人しかできません。');
        }
        return $comment;
    }

    public function updateComment($data, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        $user_id = Auth::id();
        if($comment->user_id !== $user_id) {
            throw new \Exception('コメントの編集は投稿者本人しかできません。');
        }
        $comment->fill($data->all());
        $comment->save();


        return $comment;
    }

    

    public function deleteEvent($id)
    {
        $userId = Auth::id();
        $event = Event::findOrFail($id);
        if ($event->user_id != $userId) {

            abort(403, '主催者本人でないと削除できません');
        }
        $event->delete();
    }
}
