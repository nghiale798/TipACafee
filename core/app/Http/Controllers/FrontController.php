<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Following;
use App\Models\Gallery;
use App\Models\Goal;
use App\Models\User;
use App\Models\Post;

class FrontController extends Controller
{
    protected function pageData($slug)
    {
        $user          = User::where('profile_link', $slug)->pageEnable()->with('membershipSetting')->first();
        if (!@$user) {
            return abort(404);
        }
        if (!@$user->profile_complete) {
            $notify[] = ["error", "Page not found"];
            return to_route('home')->withNotify($notify);
        }
        $galleryImages   = Gallery::published()->where('user_id', $user->id)->orderBy('id', 'DESC')->with('likes', 'comments')->withCount('likes', 'comments')->get();
        $posts           = Post::published()->searchable(['title'])->where('user_id', $user->id)->with('likes', 'comments')->withCount('likes', 'comments')->orderBy('id', 'DESC')->get();
        $membershipLevel = $user->membershipLevels()->active()->where('user_id', $user->id)->orderBy('id', 'DESC')->get();

        $isFollowing = false;
        if (auth()->check()) {
            $isFollowing = Following::where('user_id', $user->id)->where('follower_id', auth()->id())->exists();
        }
        $goal  = Goal::where('user_id', $user->id)->where('status', Status::RUNNING)->first();

        return ['galleryImages' => $galleryImages, 'posts' => $posts, 'membershipLevel' => $membershipLevel, 'isFollowing' => $isFollowing, 'hasGoal' => $goal, 'user' => $user];
    }

    public function homePage($slug)
    {

        $user = User::where('profile_link', $slug)->pageEnable()->first();
        if (!@$user) {
            return abort(404);
        }
        $pageData        = $this->pageData($slug);
        $user            = $pageData['user'];
        $pageTitle       = 'Home of' . ' ' . $user->fullname;
        $galleryImages   = $pageData['galleryImages'];
        $allPosts        = $pageData['posts'];
        $posts           = $allPosts->where('is_pinned', Status::PIN);
        $membershipLevel = $pageData['membershipLevel'];
        $isFollowing     = $pageData['isFollowing'];
        $hasGoal     = $pageData['hasGoal'];
        $donation        = $user->donationSetting;
        $recentSupports  = $user->donations->where('is_message_private', Status::NO)->whereNotNull('message')->take(20);
        $recentSupports->load(['user', 'supporter', 'member']);


        //start-seo//
        $seoContents['keywords']           = [];
        $seoContents['social_title']       = $user->creation;
        $seoContents['description']        = strLimit(strip_tags($user->about), 200);
        $seoContents['social_description'] = strLimit(strip_tags($user->about), 150);
        $seoContents['image']              = getImage(getFilePath('profileCover') . '/' . $user->cover_image, '1830x400');
        $seoContents['image_size']         = '1830x400';
        $seoContents['author']             = $user->fullname ?? '';
        $seoContents['email']              = $user->email;
        $seoContents['publishedAt']        = showDateTime($user->created_at);
        //ends-seo//

        return view($this->activeTemplate . 'profile_page.index', compact('pageTitle', 'user', 'hasGoal', 'donation', 'galleryImages', 'posts', 'recentSupports', 'membershipLevel', 'isFollowing', 'seoContents'));
    }

    public function postPage(Request $request, $slug)
    {
        $pageTitle       = 'All Posts';
        $pageData        = $this->pageData($slug);
        $user            = $pageData['user'];
        $galleryImages   = $pageData['galleryImages'];
        $posts           = $pageData['posts'];
        $membershipLevel      = $pageData['membershipLevel'];
        $isFollowing     = $pageData['isFollowing'];
        $hasGoal     = $pageData['hasGoal'];
        $popularPosts    = Post::published()->where('user_id', $user->id)->withCount('likes', 'comments')->with('likes', 'comments')->orderByRaw('likes_count + comments_count DESC')->take(5)->get();
        return view($this->activeTemplate . 'profile_page.post.index', compact('pageTitle', 'user', 'hasGoal', 'posts', 'popularPosts', 'galleryImages', 'membershipLevel', 'isFollowing'));
    }

    public function postView($link, $slug)
    {
        $pageTitle = 'Post View';
        $user      = User::where('profile_link', $link)->first();
        if (!@$user->profile_complete) {
            $notify[] = ["error", "Page not found"];
            return to_route('home')->withNotify($notify);
        }
        $pageData      = $this->pageData($user->profile_link);
        $galleryImages = $pageData['galleryImages'];
        $posts         = $pageData['posts'];
        $membershipLevel    = $pageData['membershipLevel'];
        $query         = Post::published()->where('user_id', $user->id);
        $post          = (clone $query)->where('slug', $slug)->with('likes', 'comments', 'comments.user')->withCount('likes', 'comments')->firstOrFail();
        $comments      = $post->comments->where('status', Status::ENABLE)->sortByDesc('created_at')->take(10);
        $popularPosts  = (clone $query)->where('id', '!=',  $post->id)->with('likes', 'comments')->withCount('likes', 'comments')->orderBy('id', 'DESC')->take(5)->get();


        //start-seo//
        $seoContents['keywords']     = [];
        $seoContents['social_title'] = $post->title;
        $seoContents['description']  = strLimit(strip_tags($post->content), 200);
        $seoContents['social_description'] = strLimit(strip_tags($post->content), 150);
        $seoContents['image']       = getImage(getFilePath('profileCover') . '/' . $user->cover_image, '1830x400');
        $seoContents['image_size']  = '1830x400';
        $seoContents['author']      = $user->fullname ?? '';
        $seoContents['email']       = $user->email;
        $seoContents['publishedAt'] = showDateTime($post->created_at);
        //ends-seo//

        return view($this->activeTemplate . 'profile_page.post.view', compact('pageTitle', 'user', 'post', 'comments', 'posts', 'popularPosts', 'galleryImages', 'membershipLevel',  'seoContents'));
    }

    public function filterPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'filter_type' => 'required|in:1,2,3',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $query = Post::published()->where('user_id', $request->user_id)->with('likes', 'comments')->withCount('likes', 'comments');
        if ($request->filter_type == 2) {
            $posts = $query->orderBy('id', 'ASC')->get();
        } elseif ($request->filter_type == 3) {
            $posts = $query->orderByRaw('likes_count + comments_count DESC')->get();
        } else {
            $posts = $query->orderBy('id', 'DESC')->get();
        }
        $user      = User::find($request->user_id);
        $html = view($this->activeTemplate . 'profile_page.post.list', ['posts' => $posts, 'user' =>  $user])->render();

        return response()->json([
            'success' => true,
            'html'    => $html,
        ]);
    }

    public function loadMoreComments(Request $request, $postID)
    {
        $skip = $request->offset;
        $post = Post::published()->find($postID);
        if (!@$post) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid action',
            ]);
        }
        $allComments = Comment::where('post_id', $post->id)->where('status', Status::ENABLE);
        $comments    = (clone $allComments)->orderBy('created_at', 'desc')->skip($skip)->take(5)->get();
        $html        = view($this->activeTemplate . 'profile_page.comment', ['comments' => $comments])->render();

        return response()->json([
            'success' => true,
            'html'    => $html,
            'length' => (clone $allComments)->count(),
        ]);
    }

    public function galleryPage($slug)
    {
        $pageTitle = 'Gallery Images';
        $pageData  = $this->pageData($slug);
        $user      = $pageData['user'];
        if (!@$user->profile_complete) {
            $notify[] = ["error", "Page not found"];
            return to_route('home')->withNotify($notify);
        }
        $galleryImages   = $pageData['galleryImages'];
        $posts           = $pageData['posts'];
        $membershipLevel = $pageData['membershipLevel'];
        $isFollowing     = $pageData['isFollowing'];
        $hasGoal     = $pageData['hasGoal'];

        return view($this->activeTemplate . 'profile_page.gallery.index', compact('pageTitle', 'user', 'hasGoal', 'posts', 'galleryImages', 'membershipLevel', 'isFollowing'));
    }

    public function galleryPhotoView($link, $slug, $id)
    {
        $pageTitle = 'Gallery View';
        $user      = User::where('profile_link', $link)->first();
        if (!@$user->profile_complete) {
            $notify[] = ["error", "Page not found"];
            return to_route('home')->withNotify($notify);
        }

        $pageData             = $this->pageData($user->profile_link);
        $posts                = $pageData['posts'];
        $galleryImages        = $pageData['galleryImages'];
        $membershipLevel      = $pageData['membershipLevel'];
        $gallery              = Gallery::where('id', $id)->where('slug', $slug)->with('likes', 'comments', 'comments.user')->withCount('likes', 'comments')->firstOrFail();
        $gallery->total_view += 1;
        $gallery->save();
        $comments = $gallery->comments->where('status', Status::ENABLE)->sortByDesc('created_at')->take(10);

        //start-seo//
        $seoContents['keywords']           = [];
        $seoContents['social_title']       = $gallery->title;
        $seoContents['description']        = strLimit(strip_tags($gallery->content), 200);
        $seoContents['social_description'] = strLimit(strip_tags($gallery->content), 150);
        $seoContents['image']              = getImage(getFilePath('gallery') . '/' . $gallery->image, '630x350');
        $seoContents['image_size']         = '630x350';
        $seoContents['author']             = $user->fullname ?? '';
        $seoContents['email']              = $user->email;
        $seoContents['publishedAt']        = showDateTime($gallery->created_at);
        //ends-seo//

        return view($this->activeTemplate . 'profile_page.gallery.view', compact('pageTitle', 'posts', 'galleryImages', 'user', 'gallery', 'comments', 'membershipLevel', 'seoContents'));
    }

    public function photoMoreComments(Request $request, $galleryID)
    {
        $skip = $request->offset;
        $gallery = Gallery::published()->find($galleryID);
        if (!@$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid action',
            ]);
        }
        $allComments = Comment::where('gallery_id', $gallery->id->where('status', Status::ENABLE));
        $comments    = (clone $allComments)->orderBy('created_at', 'desc')->skip($skip)->take(5)->get();
        $html        = view($this->activeTemplate . 'profile_page.post.comment', ['comments' => $comments])->render();

        return response()->json([
            'success' => true,
            'html'    => $html,
            'length'  => (clone $allComments)->count(),
        ]);
    }

    public function membershipPage($slug)
    {
        $pageTitle = 'Membership Level';
        $pageData  = $this->pageData($slug);
        $user      = $pageData['user'];
        if (!@$user->is_enable_membership) {
            return to_route('home.page', $slug);
        }
        if (!@$user->profile_complete) {
            $notify[] = ["error", "Page not found!"];
            return to_route('home')->withNotify($notify);
        }

        $galleryImages   = $pageData['galleryImages'];
        $posts           = $pageData['posts'];
        $membershipLevel = @$pageData['membershipLevel'];
        $isFollowing     = $pageData['isFollowing'];
        $hasGoal     = $pageData['hasGoal'];

        $currentMonth      = now()->startOfMonth();
        $endOfCurrentMonth = now()->endOfMonth();
        $filteredDonations = $user->donations()
            ->where('member_id', '!=', 0)
            ->whereBetween('created_at', [$currentMonth, $endOfCurrentMonth])
            ->get();
        $amountOfCurrentMonth = $filteredDonations->sum('amount');
        $totalCount           = $user->memberships()
            ->distinct('member_id')
            ->count('member_id');
        return view($this->activeTemplate . 'profile_page.membership.index', compact('pageTitle', 'user', 'hasGoal', 'posts', 'galleryImages', 'membershipLevel', 'isFollowing', 'amountOfCurrentMonth', 'totalCount'));
    }


    public function goalWidget($slug)
    {
        header("Access-Control-Allow-Origin: *");
        $user           = User::where('profile_link', $slug)->pageEnable()->first();
        $goal           = Goal::where('status', Status::RUNNING)->where('user_id', $user->id)->first();
        $collectGiftSum = $user?->goalLogs?->sum('amount') ?? 0;
        $percent        = percent($goal->starting_amount + $collectGiftSum, $goal->target_amount);
        $progressBar    = progressPercent($percent > 100 ? '100' : $percent);
        $data = [
            'profile_name'     => $user->fullname,
            'user_image'       => getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')),
            'title'            => $goal->title,
            'progress_percent' => showAmount($progressBar),
            'description'      => strLimit($goal->description,95)
        ];
        return response()->json($data);
    }
}
