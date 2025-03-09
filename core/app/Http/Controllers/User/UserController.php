<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\Comment;
use App\Models\Donation;
use App\Models\Form;
use App\Models\Gallery;
use App\Models\Like;
use App\Models\MembershipSetting;
use App\Models\Post;
use App\Models\Transaction;
use App\Models\User;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'Dashboard';
        $user      = auth()->user();
        $query             = Donation::success()->where('user_id', $user->id);
        $donations         = (clone $query)->where('supporter_id', '>', 0)->sum('amount');
        $membership        = (clone $query)->where('member_id', '>', 0)->sum('amount');
        $totalDonation     = (clone $query)->sum('amount');
        $recentSupports    = (clone $query)->with('user', 'supporter', 'member.membershipLevels', 'deposit.gateway', 'membership.level')->take(10)->orderBy('id', 'desc')->get();
        $totalGoalAchieve  = $user->deposits->where('goal_id', '>', 0)->where('donation_sign', Status::GOAL_GIFT)->sum('amount');
        $totalTransactions = Transaction::where('user_id', $user->id)->count();
        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'user', 'recentSupports', 'donations', 'membership', 'totalDonation', 'totalGoalAchieve', 'totalTransactions'));
    }

    public function getEarnings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'earning_for' => 'required|in:1,30,90',
        ]);

        $user = auth()->user();

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $earningFor = $request->input('earning_for');
        $query = Donation::success()->where('user_id', $user->id);
        $goalQuery = $user->deposits->where('goal_id', '>', 0)->where('donation_sign', Status::GOAL_GIFT);
        $trxQuery = $user->transactions;

        $baseDate = now();
        if ($earningFor != 1) {
            $baseDate = now()->subDays($earningFor)->endOfDay();
            $query = $query->where('created_at', '>', $baseDate);
            //goal&Trx
            $goalQuery  = (clone $goalQuery)->where('created_at', '>', $baseDate);
            $trxQuery = (clone $trxQuery)->where('created_at', '>', $baseDate);

        }

        $donations     = (clone $query)->where('supporter_id', '>', 0)->sum('amount');
        $membership    = (clone $query)->where('member_id', '>', 0)->sum('amount');
        $totalDonation = (clone $query)->sum('amount');
        $totalGoal     = (clone $goalQuery)->sum('amount');
        $totalTrx      = (clone $trxQuery)->count();


        return response()->json([
            'success' => true,
            'data' => [
                'total'      => $totalDonation,
                'donations'  => $donations,
                'membership' => $membership,
                'total_goal' => $totalGoal,
                'total_trx'  => $totalTrx,
            ],
        ]);
    }

    public function getStatistics(Request $request)
    {
        $userId      = auth()->id();
        $chartData   = [];
        $totalAmount = 0;
        if ($request->time == 'month') {
            foreach (getDaysOfMonth() as $day) {
                $dailyDonations = Donation::success()->where('user_id', $userId)
                    ->whereYear('created_at', now())
                    ->whereMonth('created_at', now())
                    ->whereDay('created_at', $day)
                    ->selectRaw('DATE(created_at) as date, count(*) as count, SUM(amount) as total')
                    ->groupBy('date')
                    ->first();

                $statusData['success']  = $dailyDonations->count ?? 0;
                $chartData[$day]        = $statusData;
                $totalAmount           += $dailyDonations->total ?? 0;
            }
        }

        if ($request->time == 'week') {
            $startOfWeek = now()->startOfWeek()->toDateTimeString();
            $endOfWeek   = now()->endOfWeek()->toDateTimeString();

            foreach (weekDays() as $day) {
                $dailyDonations = Donation::success()->where('user_id', $userId)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->whereDay('created_at', dayNameToDate($day))
                    ->selectRaw('DATE(created_at) as date, count(*) as count, SUM(amount) as total')
                    ->groupBy('date')
                    ->first();

                $statusData['success']  = $dailyDonations->count ?? 0;
                $chartData[$day]        = $statusData;
                $totalAmount           += $dailyDonations->total ?? 0;
            }
        }

        if ($request->time == 'year') {
            foreach (months() as $month) {
                $parsedMonth      = Carbon::parse("1 $month");
                $monthlyDonations = Donation::success()->where('user_id', $userId)
                    ->whereYear('created_at', now())
                    ->whereMonth('created_at', $parsedMonth->month)
                    ->selectRaw('MONTH(created_at) as month, count(*) as count, SUM(amount) as total')
                    ->groupBy('month')
                    ->first();
                $statusData['success'] = $monthlyDonations->count ?? 0;
                $chartData[$month] = $statusData;
                $totalAmount += $monthlyDonations->total ?? 0;
            }
        }

        // Return both chart data and the total amount
        return response()->json([
            'chart_data'   => $chartData,
            'total_amount' => $totalAmount
        ]);
    }


    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function paymentHistory(Request $request)
    {
        $pageTitle = 'Payment History';
        $user      = auth()->user();
        $query     = Donation::where(function ($query) use ($user) {
            $query->where('supporter_id', $user->id)->orWhere('member_id', $user->id);
        })->with('user', 'deposit');

        $countPayment          = (clone $query)->count();
        $lastThirtyDaysPayment = (clone $query)->where('created_at', '>', now()->subDays(30)->endOfDay())->sum('amount');
        $totalPayment          = (clone $query)->sum('amount');
        $payments              = $query->orderBy('id', 'desc')->paginate(getPaginate());

        return view($this->activeTemplate . 'user.payment_history', compact('pageTitle', 'countPayment', 'payments', 'lastThirtyDaysPayment', 'totalPayment'));
    }


    public function show2faForm()
    {
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . gs('site_name'), $secret);
        $pageTitle = '2FA Setting';
        return view($this->activeTemplate . 'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions()
    {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm()
    {
        if (auth()->user()->kv == 2) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == 1) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form = Form::where('act', 'kyc')->first();
        return view($this->activeTemplate . 'user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user = auth()->user();
        $pageTitle = 'KYC Data';
        return view($this->activeTemplate . 'user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act', 'kyc')->first();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);
        $user = auth()->user();
        $user->kyc_data = $userData;
        $user->kv = 2;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function attachmentDownload($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general = gs();
        $title = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function userData()
    {
        $user = auth()->user();
        if (@$user->profile_complete == 1) {
            return to_route('user.home');
        }
        $pageTitle = 'User Data';
        return view($this->activeTemplate . 'user.user_data', compact('pageTitle', 'user'));
    }

    public function pageNameCheck(Request $request)
    {
        $data = [];
        if ($request->link) {
            $data['exist'] = User::where('profile_link', $request->link)->exists();
            $data['link']  = $request->link;
        }
        return response($data);
    }

    public function userDataSubmit(Request $request)
    {
        $general = gs();
        $user = auth()->user();
        if (@$user->profile_complete == Status::YES) {
            return to_route('user.home');
        }
        $request->validate([
            'firstname'    => 'required',
            'lastname'     => 'required',
            'profile_link' => 'required',
            'about'        => 'required',
            'website_link' => 'nullable|url',
            'image'        => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ]);

        $exist = User::where('profile_link', $request->profile_link)->first();

        if ($exist) {
            $notify[] = ['error', 'The profile link is already exists!'];
            return back()->withNotify($notify);
        }

        if ($request->hasFile('image')) {
            try {
                $image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload page image'];
                return back()->withNotify($notify);
            }
        }

        $purifier = new \HTMLPurifier();

        $user->firstname         = $request->firstname;
        $user->lastname          = $request->lastname;
        $user->profile_complete  = Status::YES;
        $user->profile_link      = strtolower(trim($request->profile_link));
        $user->about             =  $purifier->purify($request->about);
        $user->website_link      = $request->website_link;
        $user->image             = $image;
        $user->donate_emoji      = $general->emoji;
        $user->donate_emoji_name = $general->emoji_name;
        $user->save();

        $notify[] = ['success', 'Profile data updated successfully'];
        return to_route('home.page', $user->profile_link)->withNotify($notify);
    }

    public function userPageUpdate(Request $request)
    {
        // dd($request->all());

        $user = auth()->user();
        if ($user->profile_complete == Status::NO) {
            return to_route('user.home');
        }
        $imageRule = $user->image ? 'nullable' : 'required';
        $request->validate([
            'firstname'         => 'required',
            'lastname'          => 'required',
            'creation'          => 'required|max:255',
            'about'             => 'required',
            'donate_emoji'      => 'required',
            'donate_emoji_name' => 'required',
            'custom_color'      => $request->theme_color ? 'nullable' : 'required',
            'theme_color'       => $request->custom_color ? 'nullable' : 'required',
            'theme_color_name' => 'required',
            'link.*'           => 'nullable|url',
            'profile_image'       => [$imageRule, 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ],[
            'link.*.url' => 'Provided invalid social link!'
        ]);

        if ($request->hasFile('profile_image')) {
            try {
                $old = $user->image;
                $user->image = fileUploader($request->profile_image, getFilePath('userProfile'), getFileSize('userProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload page image'];
                return back()->withNotify($notify);
            }
        }
        $user->firstname               = $request->firstname;
        $user->lastname                = $request->lastname;
        $user->creation                = $request->creation;
        $user->about                   = $request->about;
        $user->donate_emoji            = $request->donate_emoji;
        $user->donate_emoji_name       = $request->donate_emoji_name;
        $user->theme_color             = $request->theme_color ?? $request->custom_color;
        $user->theme_color_name        = $request->theme_color_name;

        $socialLinks = [];
        if ($request->has('link') && is_array($request->link)) {
            foreach ($request->link as $link) {
                if ($link && trim($link) !== '') {
                    $socialLinks[] = [
                        'link' => $link,
                    ];
                }
            }
        }

        $user->social_links = $socialLinks;
        $user->save();
        $notify[] = ['success', 'Page updated successfully'];
        return back()->withNotify($notify);
    }

    public function profileCoverImage(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'cover_image'   => ['required_if:profile_image,null', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png']),],
            'profile_image' => ['required_if:cover_image,null', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png']),],
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }

        $user =  auth()->user();

        if ($request->hasFile('cover_image')) {
            try {
                $old               = $user->cover_image;
                $user->cover_image = fileUploader($request->cover_image, getFilePath('profileCover'), getFileSize('profileCover'), $old);
            } catch (\Exception $exp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Couldn\'t upload profile cover image',
                ]);
            }
        }

        if ($request->hasFile('profile_image')) {
            try {
                $old         = $user->image;
                $user->image = fileUploader($request->profile_image, getFilePath('userProfile'), getFileSize('userProfile'), $old);
            } catch (\Exception $exp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Couldn\'t upload profile image',
                ]);
            }
        }
        $user->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function getPostLike(Request $request)
    {
        $post = Post::published()->where('id', $request->post_id)->first();
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'No post found',
            ]);
        }

        $user   = auth()->user();
        $like   = Like::where('user_id', $user->id)->where('post_id', $post->id)->first();
        $isLike = false;

        if ($like) {
            $like->delete();
        } else {
            $isLike =  true;
            $like = new Like();
            $like->user_id = $user->id;
            $like->post_id = $post->id;
            $like->save();
        }

        $countLikes = Like::where('post_id', $post->id)->count();

        return response()->json([
            'success'    => true,
            'is_like'    => $isLike,
            'count_like' => $countLikes,
        ]);
    }

    public function deleteComment($id)
    {
        $comment = Comment::where('user_id', auth()->id())->findOrFail($id);
        $comment->delete();
        $notify[] = ['success', 'Comment deleted successfully'];
        return back()->withNotify($notify);
    }

    //Gallery-area
    public function getGalleryLike(Request $request)
    {
        $gallery = Gallery::published()->where('id', $request->gallery_id)->first();
        if (!@$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'No gallery photo added',
            ]);
        }
        $user   = auth()->user();
        $like   = Like::where('user_id', $user->id)->where('gallery_id', $gallery->id)->first();

        $isLike = false;
        if ($like) {
            $like->delete();
        } else {
            $isLike =  true;
            $like = new Like();
            $like->user_id = $user->id;
            $like->gallery_id = $gallery->id;
            $like->save();
        }

        $countLikes = Like::where('gallery_id', $gallery->id)->count();

        return response()->json([
            'success'    => true,
            'is_like'    => $isLike,
            'count_like' => $countLikes,
        ]);
    }

    public function storeComment(Request $request, $id)
    {
        $ruleValidation = $request->comment_id ? 'required' : 'nullable';
        $validation  = Validator::make($request->all(), [
            'comment'  => 'required',
            'comment_id'  => $ruleValidation,
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }

        if ($request->is_post) {
            $post = Post::published()->find($id);
            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'No post found',
                ]);
            }
        } else {
            $gallery = Gallery::published()->find($id);
            if (!$gallery) {
                return response()->json([
                    'success' => false,
                    'message' => 'No gallery photo added',
                ]);
            }
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first!',
            ]);
        }

        if ($request->comment_id) {
            $comment = Comment::where('user_id', $user->id)->find($request->comment_id);
            if (!$comment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid comment action!',
                ]);
            }
        } else {
            $comment = new Comment();
            $comment->post_id = $post->id ?? 0;
            $comment->gallery_id = $gallery->id ?? 0;
            $comment->user_id = $user->id;
        }

        $comment->comment = $request->comment;
        $comment->save();

        if (!$request->comment_id) {
            $html = view($this->activeTemplate . 'profile_page.comment', ['comment' => $comment, 'skeleton' => true])->render();
        }

        return response()->json([
            'success' => true,
            'comment' => $comment->comment,
            'html'    => $html ?? "",
        ]);
    }

    public function membership()
    {
        $user = auth()->user();

        if (@$user->is_enable_membership) {
            return to_route('user.membership.index');
        }

        $membership = MembershipSetting::where('user_id', $user->id)->first();

        if (!@$membership) {
            $setting          = new MembershipSetting();
            $setting->user_id = $user->id;
            $setting->save();
        }

        $pageTitle = "Manage Membership";
        return view($this->activeTemplate . 'user.membership.membership', compact('pageTitle'));
    }

    public function gallaryImageDownload($id)
    {
        $download  = Gallery::findOrFail($id);
        $file      = $download->image;
        $path      = getFilePath('gallery');
        $full_path = $path . '/' . $file;
        $title     = slug($download->slug);
        $ext       = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype  = mime_content_type($full_path);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }

    //A/C-setting
    public function accountSetting()
    {
        $user = auth()->user();
        $pageTitle = 'Account Setting';
        return view($this->activeTemplate . 'user.account_setting', compact('pageTitle', 'user'));
    }

    public function accountSettingStore(Request $request)
    {
        $user = auth()->user();
        $user->show_supporter_count = $request->show_supporter_count;
        $user->save();
        return response()->json([
            'success' => true
        ]);
    }

    public function accountDeactivate()
    {
        $user                   = auth()->user();
        $user->account_disabled = Status::YES;
        $user->save();
        auth()->guard()->logout();
        return to_route('home');
    }
}
