<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\GoalLog;
use Illuminate\Http\Request;

class GoalSettingController extends Controller {
    public function index() {
        $pageTitle   = "Set Goal";
        $user        = auth()->user();
        $runningGoal = Goal::where('user_id', $user->id)->where('status', Status::RUNNING)->first();
        $allGoals    = Goal::where('user_id', $user->id)->withSum('goalLogs', 'amount')->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.goal.index', compact('pageTitle', 'runningGoal', 'allGoals'));
    }

    public function store(Request $request) {
        $request->validate([
            'title'           => 'required|string',
            'description'     => 'required|string',
            'target_amount'   => 'required|numeric|gt:starting_amount',
            'starting_amount' => 'required|numeric|lt:target_amount',
            'thanks_message'  => 'required|string',
        ]);
        $user = auth()->user();
        $goal = Goal::where('user_id', $user->id)->where('status', Status::RUNNING)->first();
        if ($goal && !$request->id) {
            $notify[] = ['error', 'Something went wrong!'];
            return back()->withNotify($notify);
        }
        if ($goal && $request->id) {
            $notification = "Set Goal updated successfully!";
        } else {
            $notification  = "Goal Set successfully!";
            $goal          = new goal();
            $goal->user_id = $user->id;
        }
        $goal->title           = $request->title;
        $goal->description     = $request->description;
        $goal->target_amount   = $request->target_amount;
        $goal->starting_amount = $request->starting_amount;
        $goal->view_publicly   = $request->view_publicly == "on" ? 1 : 0;
        $goal->thanks_message  = $request->thanks_message;
        $goal->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function statusCancelEnable($id, $flag) {
        $user = auth()->user();
        $goal = Goal::where('user_id', $user->id)->where('status', '!=', Status::COMPLETED)->findOrFail($id);
        if ($flag == Status::RUNNING) {
            $notification = 'Goal running successfully';
            $goal->status = Status::RUNNING;
        } else {
            $notification = 'Goal cancelled successfully';
            $goal->status = Status::DISABLE;
        }
        $goal->save();
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function complete($id) {
        $goal         = Goal::where('user_id', auth()->id())->where('status', Status::RUNNING)->findOrFail($id);
        $goal->status = Status::COMPLETED;
        $goal->save();
        $notify[] = ['success', 'Goal status completed successfully!'];
        return back()->withNotify($notify);
    }

    public function giftLog() {
        $pageTitle = "Goal Gift Log";
        $user      = auth()->user();
        $goals     = Goal::where('user_id', auth()->id())->get();
        $goalLogs  = GoalLog::where('user_id', auth()->id())->searchable(['trx'])->filter(['goal_id'])->with(['goal'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.goal.history', compact('pageTitle', 'goals', 'goalLogs'));
    }
}
