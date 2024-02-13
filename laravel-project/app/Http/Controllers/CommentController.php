<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommentService;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }



    public function create(Request $request, $event_id)
    {
        $this->commentService->postComment($request, $event_id);
        return back();
    }

    public function edit($comment_id)
    {
        $comment = $this->commentService->getById($comment_id);
        return view('comments.edit', ['comment' => $comment]);
    }

    public function update(Request $request, $comment_id)
    {
        try {
            $comment = $this->commentService->updateComment($request, $comment_id);
            $event_id = $comment->event_id;
            return redirect()->route('events.show', ['event' => $event_id]);
        } catch (\Exception $e) {
            Log::error("Comment update failed: " . $e->getMessage(), ['comment_id' => $comment_id]);
            return redirect()->back()->withErrors(['custom_error' => $e->getMessage()])->withInput();
        }
    }
}
