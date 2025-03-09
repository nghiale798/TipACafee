<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Following;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ManagePostController extends Controller
{
    public function index()
    {
        $pageTitle    = "Manage Post";
        $publishPosts = $this->posts(Status::PUBLISH);
        $draftPosts   = $this->posts(Status::DRAFT);
        return view($this->activeTemplate . 'user.post.index', compact('pageTitle', 'publishPosts', 'draftPosts'));
    }

    protected function posts($status)
    {
        return Post::where('user_id', auth()->id())
            ->with('user')
            ->where('status', $status)
            ->withCount('likes', 'comments')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());
    }

    public function create()
    {
        $pageTitle  = "Create Post";
        $user       = auth()->user();
        $categories = Category::active()->get();
        return view($this->activeTemplate . 'user.post.form', compact('pageTitle', 'user', 'categories'));
    }

    public function edit($id)
    {
        $pageTitle  = "Edit Post";
        $user       = auth()->user();
        $categories = Category::active()->get();
        $post       = Post::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        return view($this->activeTemplate . 'user.post.form', compact('pageTitle', 'post', 'user', 'categories'));
    }

    public function store(Request $request, $id = 0)
    {
        $validation  = Validator::make($request->all(), [
            'title'   => 'required',
            'content' => 'required|string',
            'visible' => 'required|in:1,2,3',
            'status'  => 'required|in:0,1',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }

        if ($request->category_id) {
            $category = Category::active()->where('id', $request->category_id)->first();
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => "Category not found"
                ]);
            }
        }
        $user = auth()->user();

        if ($id) {
            $post         = Post::where('user_id', $user->id)->findOrFail($id);
            $notification = 'Post updated successfully';
        } else {
            $post          = new Post();
            $post->user_id = $user->id;
            $notification  = 'Post created successfully';
        }



        $purifier = new \HTMLPurifier();
        $words             = explode(' ', $request->title);
        $first3Words       = array_slice($words, 0, 3);
        $slugTitle         = implode('-', $first3Words);

        $post->category_id = $request->category_id ?? 0;
        $post->title       = $request->title;
        $post->slug        = slug($slugTitle . '-' . $post->id);
        $post->content     = $purifier->purify($request->content);
        $post->status      = $request->status;
        $post->visible     = $request->visible;
        $post->save();

        if ($request->notify_followers == "on" && $post->status == Status::PUBLISH) {
            $followers = Following::where('user_id',  $user->id)->orderBy('id', 'desc')->get();
            if (@$followers) {
                foreach ($followers as $follower) {
                    $following  =  User::active()->where('id', $follower->follower_id)->first();
                    if ($following) {
                        notify($user, 'NOTIFY_FOLLOWERS', [
                            'author'       => $user->fullname,
                            'type'         => "post",
                            'link'         => route('post.view', [$user->profile_link, $post->slug]),
                            'published_at' => $post->created_at,
                        ]);
                    }
                    continue;
                }
            }
        }

        return response()->json([
            'success'      => true,
            'redirect_url' => route('user.post.edit', $post->id),
            'is_publish'   => $post->status == Status::PUBLISH,
            'message'      => $notification,
        ]);
    }

    public function delete($id)
    {
        $post = Post::where('user_id', auth()->user()->id)->findOrFail($id);
        $likes = Like::where('post_id', $id)->get();
        if (!blank($likes)) {
            foreach ($likes as $like) {
                $like->delete();
            }
        }
        $comments = Comment::where('post_id', $id)->get();
        if (!blank($comments)) {
            foreach ($comments as $comment) {
                $comment->delete();
            }
        }
        $post->delete();
        $notify[] = ['success', 'Post deleted successfully'];
        return back()->withNotify($notify);
    }

    public function changePinStatus($id)
    {
        $post = Post::where('user_id', auth()->user()->id)->findOrFail($id);

        if ($post->status == Status::PUBLISH) {
            $pinPost = Post::where('status', Status::PUBLISH)->where('is_pinned', Status::PIN)->first();
        } else {
            $pinPost = Post::where('status', Status::DRAFT)->where('is_pinned', Status::PIN)->first();
        }

        if ($pinPost) {
            if ($post->id == $pinPost->id) {
                $post->is_pinned = Status::UNPIN;
            } else {
                $pinPost->is_pinned = Status::UNPIN;
                $pinPost->save();
                $post->is_pinned = Status::PIN;
            }
        } else {
            $post->is_pinned = Status::PIN;
        }
        $post->save();

        $notify[] = ['success', 'Pin status changed successfully'];
        return back()->withNotify($notify);
    }
}
