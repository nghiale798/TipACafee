<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Following;
use App\Models\Gallery;
use App\Models\Like;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Rules\FileTypeValidate;
use Image as ImageFacade;
use Illuminate\Http\Request;

class ManageGalleryController extends Controller
{
    public function index()
    {
        $pageTitle      = "Manage Gallery";
        $publishGallary = $this->gallery(Status::PUBLISH);
        $draftGallery   = $this->gallery(Status::DRAFT);
        return view($this->activeTemplate . 'user.gallery.index', compact('pageTitle', 'publishGallary', 'draftGallery'));
    }

    protected function gallery($status)
    {
        return Gallery::where('user_id', auth()->id())
            ->where('status', $status)
            ->withCount('likes', 'comments')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());
    }

    public function store(Request $request)
    {
        $isValidation = $request->id ? 'nullable' : 'required';
        $validation  = Validator::make($request->all(), [
            'image'   => [$isValidation, new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'title'   => 'required',
            'content' => 'nullable|string',
            'visible' => 'required|in:1,2,3',
            'status'  => 'required|in:0,1',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }
        $user             = auth()->user();

        if ($request->id) {
            $gallery =  Gallery::where('id', $request->id)->where('user_id', $user->id)->first();
            if (!$gallery) {
                return response()->json([
                    'success' => false,
                    'message' => 'This action is invalid to process!',

                ]);
            }
        } else {
            $gallery =  new Gallery();
        }

        if ($request->hasFile('image')) {

            try {
                $thumb = ImageFacade::make($request->image);
                $thumb->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $gallery->image_width = $thumb->width();
                $gallery->image_height = $thumb->height();
                $gallery->image = fileUploader($request->image, getFilePath('gallery'), getFileSize('gallery'), $gallery->oldImage ?? null);
            } catch (\Exception $exp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Couldn\'t upload gallery photo!',
                ]);
            }
        }

        $purifier = new \HTMLPurifier();

        $words            = explode(' ', $request->title);
        $first3Words      = array_slice($words, 0, 3);
        $slugTitle        = implode('-', $first3Words);

        $gallery->user_id = $user->id;
        $gallery->title   = $request->title;
        $gallery->slug    = slug($slugTitle);
        $gallery->content = $purifier->purify($request->content);
        $gallery->status  = $request->status;
        $gallery->visible = $request->visible;
        $gallery->save();

        $publish = false;
        if ($gallery->status == Status::PUBLISH) {
            $publish = true;
        }

        if ($request->notify_followers == "on" && $gallery->status == Status::PUBLISH) {
            $followers = Following::where('user_id',  $user->id)->orderBy('id', 'desc')->get();
            if (@$followers) {
                foreach ($followers as $follower) {
                    $following  =  User::active()->where('id', $follower->follower_id)->first();
                    if ($following) {
                        notify($user, 'NOTIFY_FOLLOWERS', [
                            'author'       => $user->fullname,
                            'type'         => "photo",
                            'link'         => route('gallery.view', [$user->profile_link, $gallery->slug, $gallery->id]),
                            'published_at' => $gallery->created_at,
                        ]);
                    }
                    continue;
                }
            }
        }

        return response()->json([
            'success'      => true,
            'is_publish'   => $publish,
            'redirect_url' => route('user.gallery.index'),
        ]);
    }


    public function delete($id)
    {
        $gallery = Gallery::where('user_id', auth()->user()->id)->findOrFail($id);
        $likes   = Like::where('gallery_id', $id)->get();

        if (!blank($likes)) {
            foreach ($likes as $like) {
                $like->delete();
            }
        }

        $comments = Comment::where('gallery_id', $id)->get();
        if (!blank($comments)) {
            foreach ($comments as $comment) {
                $comment->delete();
            }
        }

        $gallery->delete();
        $notify[] = ['success', 'Gallery photo deleted Successfully'];
        return back()->withNotify($notify);
    }

    public function changePinStatus($id)
    {
        $gallery = Gallery::where('user_id', auth()->user()->id)->findOrFail($id);

        if ($gallery->status == Status::PUBLISH) {
            $pinGallery = Gallery::where('status', Status::PUBLISH)->where('is_pinned', Status::PIN)->first();
        } else {
            $pinGallery = Gallery::where('status', Status::DRAFT)->where('is_pinned', Status::PIN)->first();
        }

        if ($pinGallery) {
            if ($gallery->id == $pinGallery->id) {
                $gallery->is_pinned = Status::UNPIN;
            } else {
                $pinGallery->is_pinned = Status::UNPIN;
                $pinGallery->save();
                $gallery->is_pinned = Status::PIN;
            }
        } else {
            $gallery->is_pinned = Status::PIN;
        }

        $gallery->save();
        return back();
    }

    public function changedStatus($id)
    {
        $gallery     = Gallery::where('user_id', auth()->id())->findOrFail($id);
        if ($gallery->status == Status::DRAFT) {
            $gallery->status = Status::PUBLISH;
            $message         = 'Gallery photo published successfully';
        } else {
            $gallery->status = Status::DRAFT;
            $message       = 'Gallery photo drafted successfully';
        }
        $gallery->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }
}
