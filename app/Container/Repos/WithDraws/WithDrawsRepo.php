<?php

/**
 * Created by PhpStorm.
 * User: Backend Dev
 * Date: 5/17/2018
 * Time: 11:30 AM
 */

namespace Repos\WithDraws;


use App\Schedule;
use App\Withdraw;
use Contracts\WithDraws\WithDrawsContract;
use Contracts\Options\OptionsContract;

class WithDrawsRepo implements WithDrawsContract
{
    public function __construct(
        Schedule $schedule,
        Withdraw $withdraw,
        OptionsContract $option
    ) {
        $this->schedule = $schedule;
        $this->withdraw = $withdraw;
        $this->option = $option;
    }

    public function get($id)
    {
        return $this->withdraw->findOrFail($id);
    }

    public function getAvailableWithdarw($teacher_id)
    {
        $total_amount = $this->schedule
            ->where('payed', 1)
            ->where('teacher_id', $teacher_id)
            ->sum('price');

        // $withdrawed_amount = $this->withdraw
        //     ->where('user_id', $teacher_id)
        //     ->where('status', 1)
        //     ->sum('amount');

        $withdrawed_amount = $this->withdraw
            ->selectRaw('SUM(amount) as amount, status')
            ->where('user_id', $teacher_id)
            ->groupBy('status')
            ->get();

        if (in_array(2, array_column($withdrawed_amount->toArray(), 'status'))) {
            return 0;
        } else {
            $amounts_array = $withdrawed_amount->pluck('amount', 'status')->toArray();

            $paid_amount = (in_array(1, array_keys($amounts_array))) ? $amounts_array[1] : 0;
            return $total_amount - $paid_amount;
        }

    }
    public function requestWithdarw($teacher_id)
    {
        return $this->withdraw->create([
            'user_id' => $teacher_id,
            'amount' => $this->getAvailableWithdarw($teacher_id),
            'status' => 2,
            'percent' => $this->option->getByName('percent')->value,
        ]);
    }
    public function changeStatus($id, $status)
    {
        return $this->get($id)->update([
            "status" => $status,
            "confirmed_at" => ($status) ? \Carbon\Carbon::now() : null
        ]);
    }

    public function getRequestedByUserIds($user_ids)
    {
        $result = [];
        $withdraw_arr = $this->withdraw
            ->whereIn('user_id', $user_ids)
            ->where('status', 2)
            ->whereNull('confirmed_at')->get();

        foreach ($withdraw_arr as $key => $value) {
            $result[$value->user_id] = [
                'id' => $value->id,
                'amount' => $value->amount,
                'percent' => $value->percent
            ];
        }

        return $result;
    }
}