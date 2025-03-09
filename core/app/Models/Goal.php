<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::RUNNING) {
                $html = '<span class="badge badge--base">' . trans('Running') . '</span>';
            }elseif ($this->status == Status::DISABLE) {
                $html = '<span class="badge badge--danger">' . trans('Cancelled') . '</span>';
            }else {
                $html = '<span class="badge badge--success">' . trans('Completed') . '</span>';
            }
            return $html;
        });

    }
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
    public function goalLogs()
    {
        return $this->hasMany(GoalLog::class);
    }
}
