<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Gallery;

class GalleryManageController extends Controller
{

    public function index()
    {
        $pageTitle = 'Gallery Photo List';
        $galleryImages = Gallery::searchable(['title', 'user:username'])->dateFilter('created_at')->filter(['status', 'visible'])->with('user')->withCount('comments')->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('admin.gallery.index', compact('pageTitle', 'galleryImages'));
    }

    public function details($id)
    {
        $pageTitle = 'Gallery Photo Details';
        $photo     = Gallery::where('id', $id)->with('likes', 'comments', 'comments.user')->withCount('likes', 'comments')->firstOrFail();
        return view('admin.gallery.details', compact('pageTitle', 'photo'));
    }

    public function comment($id)
    {
        $gallery   = Gallery::findOrFail($id);
        $comments  = Comment::where('gallery_id', $gallery->id)->with('user')->searchable(['comment', 'user:username'])->orderBy('id', 'DESC')->paginate(getPaginate());
        $pageTitle = "Comments of #" . strLimit($gallery->title, 30);
        return view('admin.gallery.comment', compact('pageTitle', 'comments', 'id'));
    }
    public function status($id)
    {
        return Comment::changeStatus($id);
    }
}
