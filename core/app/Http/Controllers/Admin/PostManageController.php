<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;

class PostManageController extends Controller
{
    public function index()
    {
        $pageTitle = 'Post List';
        $posts = Post::searchable(['title', 'user:username'])->dateFilter('created_at')->filter(['status', 'visible'])->with('user')->withCount('comments')->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('admin.post.index', compact('pageTitle', 'posts'));
    }

    public function details($id)
    {
        $pageTitle = 'Gallery Photo Details';
        $post     = Post::where('id', $id)->with('likes', 'comments', 'comments.user')->withCount('likes', 'comments')->firstOrFail();
        return view('admin.post.details', compact('pageTitle', 'post'));
    }

    public function comment($id)
    {
        $post      = Post::findOrFail($id);
        $comments  = Comment::where('post_id', $post->id)->with('user')->searchable(['comment', 'user:username'])->orderBy('id', 'DESC')->paginate(getPaginate());
        $pageTitle = "Comments of #" . strLimit($post->title, 30);
        return view('admin.post.comment', compact('pageTitle', 'comments', 'id'));
    }
    public function status($id)
    {
        return Comment::changeStatus($id);
    }
}
