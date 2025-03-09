<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Following;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExploreCreatorController extends Controller
{
    public function index()
    {
        $pageTitle   = "Explore Creator";
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $trendingCreators = User::pageEnable()->withCount(['followers as new_followers_count' => function ($query) use ($startOfWeek, $endOfWeek) {
            $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        }])
            ->with('donations')->orderByDesc('new_followers_count')
            ->take(10)
            ->get();

        $sevenDaysAgo = Carbon::now()->subDays(7);

        $users = User::where('id', '!=', auth()->id())
            ->where(function ($query) use ($sevenDaysAgo) {
                $query->whereHas('posts', function ($subQuery) use ($sevenDaysAgo) {
                    $subQuery->published()->where('created_at', '>=', $sevenDaysAgo);
                })
                    ->orWhereHas('galleries', function ($subQuery) use ($sevenDaysAgo) {
                        $subQuery->published()->where('created_at', '>=', $sevenDaysAgo);
                    });
            })
            ->with([
                'posts' => function ($query) use ($sevenDaysAgo) {
                    $query->published()->where('created_at', '>=', $sevenDaysAgo)->latest()->limit(2);
                },
                'galleries' => function ($query) use ($sevenDaysAgo) {
                    $query->published()->where('created_at', '>=', $sevenDaysAgo)->latest()->limit(2);
                }
            ])->paginate(getPaginate());

        $users->each(function ($user) {
            $postsCount = $user->posts->count();
            $galleryCount = $user->galleries->count();

            if ($postsCount > 0 && $galleryCount > 0) {
                $user->latestPosts = $user->posts->take(1)->merge($user->galleries->take(1));
            } elseif ($postsCount == 0 && $galleryCount > 0) {
                $user->latestPosts = $user->galleries->take(2);
            } elseif ($postsCount > 0 && $galleryCount == 0) {
                $user->latestPosts = $user->posts->take(2);
            } else {
                $user->latestPosts = collect();
            }
        });

        return view($this->activeTemplate . 'user.explore.index', compact('pageTitle', 'trendingCreators', 'users'));
    }

    public function followers()
    {
        $pageTitle = "Explore Followers";
        $followers = Following::where('user_id', auth()->id())->with('follower')->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate .  'user.explore.followers', compact('pageTitle', 'followers'));
    }
    public function following()
    {
        $pageTitle  = "Explore Following";
        $followings = Following::where('follower_id', auth()->id())->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate .  'user.explore.following', compact('pageTitle', 'followings'));
    }

    public function toggleFollow(Request $request)
    {
        $followerId = $request->follower_id;
        $user       = User::where('id', $request->user_id)->active()->pageEnable()->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "User not found!"
            ]);
        }

        $toggleFollow = Following::where('user_id', $user->id)->where('follower_id', $followerId)->first();
        if ($toggleFollow) {
            $toggleFollow->delete();
            $action  = 'unfollow';
            $message = "Unfollow {$user->profile_link} successfully";
        } else {
            $toggleFollow              = new Following();
            $toggleFollow->user_id     = $user->id;
            $toggleFollow->follower_id = $followerId;
            $toggleFollow->save();
            $action  = 'follow';
            $message = "Following {$user->profile_link} successfully";
        }
        return response()->json(
            [
                'success' => true,
                'action'  => $action,
                'message' => $message
            ]
        );
    }

    public function creators(Request $request)
{
    $scrollType = $request->scroll_type;
    $id = $request->id;
    $users = User::active()->pageEnable()->searchable(['firstname', 'lastname', 'profile_link'])->withCount('followers')->orderBy('followers_count', 'desc');
    if ($scrollType == 'down') {
        $users = $users->where('id', '>', $id);
    }
    $users = $users->paginate(getPaginate());

    return response()->json([
        'success' => true,
        'creators' => $users->items(),
        'nextPageUrl' => $users->nextPageUrl(),
    ]);
}

}
