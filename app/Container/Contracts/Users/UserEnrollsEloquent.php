<?php

/**
 * Created by PhpStorm.
 * User: Backend Dev
 * Date: 5/17/2018
 * Time: 11:31 AM
 */

namespace App\Container\Contracts\Users;

use App\User;
use App\Models\Schedule;
use App\Models\Lecture;
use App\Models\Order;
//use Contracts\Payments\PaymentsContract;
//use Contracts\Services\TokBox\TokBoxContract;
use App\Container\Contracts\Users\UserEnrollsContract;
//use Contracts\Payments\FawryContract;
use Carbon\Carbon;

class UserEnrollsEloquent implements UserEnrollsContract
{
    public $number_of_pages = 1;
    public function __construct(
        Schedule $schedule,
        Lecture $lecture,
        Order $order
       // PaymentsContract $payment,
       // TokBoxContract $tokbox,
        //FawryContract $fawry

    ) {
        $this->schedule        = $schedule;
        $this->lecture         = $lecture;
        $this->order           = $order;
//        $this->payment         = $payment;
//        $this->tokbox          = $tokbox;
        $this->number_of_pages = config('static.pagination.rowsPerPage');
//        $this->fawry           = $fawry;
    }

   /* public function getPendingItems($user_id)
    {
        return $this->schedule
            ->where('user_id', $user_id)
            ->where('payed', '!=', 1)
            ->where('status', 'pending')
            ->with('teachers')
            ->with('attendees')
            ->with('sub_attendees')
            ->whereRaw('CONCAT(`date`," ",`time_to`) >= "' . \Carbon\Carbon::now()->format("Y-m-d H:i:s") . '"')
            ->get()->groupBy('lecture_id');

    }

    public function getUnPaid($user_id)
    {
        return $this->schedule
            ->where('user_id', $user_id)
            ->where('payed', '!=', 1)
            ->where('status', null)
            ->with('teachers')
            ->with('attendees')
            ->with('sub_attendees')
            ->whereRaw('CONCAT(`date`," ",`time_to`) >= "' . \Carbon\Carbon::now()->format("Y-m-d H:i:s") . '"')
            ->get()->groupBy('lecture_id');

    }

    public function getPaid($user_id)
    {
        return $this->schedule
            ->where('user_id', $user_id)
            ->where('payed', '=', 1)
            ->with('teachers')
            ->with('attendees')
            ->with('sub_attendees')
            ->get();
    }

    public function removeItem($user_id, $id)
    {
        return $this->schedule->where('user_id', $user_id)
        ->where('payed', '!=', null)
        ->where('id', $id)->delete();
    }

    public function removeGroupItem($user_id, $lecture_id)
    {
        return $this->schedule->where('user_id', $user_id)
        ->where('lecture_id', $lecture_id)
        ->where('payed', '!=', null)->where('type' , 'group')->delete();
    }


    public function enroll($user_id, $lecture, $data)
    {
        $this->lecture = $lecture;

        if (!$this->lecture->attendees->contains($user_id)) {
            $this->lecture->attendees()->attach($user_id, [
                "teacher_id" => $this->lecture->teacher_id,
                "date" => $this->lecture->date,
                "time_from" => $this->lecture->time_from,
                "time_to" => $this->lecture->time_to,
                "price" => ($data->type == 'group') ?
                    $this->lecture->teacher->profile->price_info['group'] :
                    $this->lecture->teacher->profile->price_info['individual'],
                "type" => ($data->type == 'group') ? 'group' : 'individual',
            ]);
        }
        if (isset($data->sub_users)) {
            $detail = [
                "user_id" => $user_id,
                "teacher_id" => $this->lecture->teacher_id,
                "date" => $this->lecture->date,
                "time_from" => $this->lecture->time_from,
                "time_to" => $this->lecture->time_to,
                "price" => $this->lecture->teacher->profile->price_info['group'] ?? 0,
                "type" => 'group',
            ];

            $subusers = array_map(function ($item) use ($detail) {
                $detail['sub_user_id'] = $item;
                return $detail;
            }, json_decode($data->sub_users));


            $old_sub_user_ids = $this->lecture->sub_attendees()
                ->where('schedules.user_id', $user_id)->pluck('sub_user_id');

            $this->lecture->sub_attendees()->detach($old_sub_user_ids);
            $this->lecture->sub_attendees()->attach($subusers);
        }

        return $this->lecture->attendees->contains($user_id);

    }

    public function checkout($user_id, $data)
    {
        $lectures = $this->lecture
            ->whereIn('id', $data->items)
            ->where(function ($query) use ($user_id) {
                return $query
                    ->whereNull('checkout_user_id')
                    ->orWhere('checkout_user_id', $user_id);
            })->get();

        $checkoutUsers = $this->schedule->whereIn('lecture_id', $data->items)->where('user_id', '!=' , $user_id)->get()->toArray();
        if (!$checkoutUsers) {
            $this->lecture
                ->whereIn('id', $data->items)
                ->whereNull('checkout_user_id')->update([
                    'checkout_user_id' => $user_id,
                ]);

            $items_details = $this->schedule
                ->where('user_id', $user_id)
                ->where('status', null)
                ->whereIn('lecture_id', $data->items)
                ->get();

            $items = $this->schedule
                ->where('user_id', $user_id)
                ->whereIn('lecture_id', $data->items)
                ->get()->toArray();

            $items_price = $items_details->sum('price');

            $total_amount = (isset($data->percent))? $items_price * (1 - ($data->percent / 100)): $items_price;

            $dataItems = [];
            $fawryExpiryHours = [];

            foreach($items as $key => $item){
                $dataItems[$key]['lecture_id'] = $item['lecture_id'];
                $dataItems[$key]['quantity'] = '1';
                $dataItems[$key]['price'] =  (isset($data['percent'])) ? number_format($item['price'] * (1 -($data['percent'] / 100)), 2, '.', '') : $item['price'];
                $itemDate = Carbon::parse($item['date'] .' '. $item['time_to']);
                $itemNow = Carbon::parse(Carbon::now()->format('Y-m-d H:i:s'));
                $fawryExpiryHours[$key] = $itemDate->diffInHours($itemNow);
            }


            $order = $this->order->create([
                'amount'      => number_format($total_amount, 2, '.', ''),
                'lectures'    => json_encode($dataItems),
                'description' => "Payed Sessions for " . implode(' and ', $lectures->pluck('date')->toArray()),
                'unique_id'   => time() . rand(1111, 9999),
                'user_id'     => \Auth::user()->id,
                'merchant_reference_number'   => time() . rand(1111, 9999),
                'payment_method' => $data->payment_method,
                'expiry_hours'   => (min($fawryExpiryHours) >= 24) ? '24' : min($fawryExpiryHours)
            ]);

            if ($total_amount == 0)
                return $order;
            
            if ($data->payment_method == 'fawry') {
                return $this->fawry->checkout($order);
            }
            return $this->payment->checkout($order);
        }

        return null;
    }

    // private function __booked($data)
    // {
    //     $payment_response = $this->payment->response($data);
    //     dd($payment_response);
    //     if ($payment_response) {
    //         $flag_1 = $this->schedule
    //             ->where('lecture_id', $data->lecture_id)
    //             ->where('user_id', $data->user_id)
    //             ->update(['payed', true]);
    
    //             //delete others
    //         $this->schedule->where('lecture_id', $data->lecture_id)
    //             ->where('user_id', '!=', $data->user_id)
    //             ->delete();

    //         $flag_2 = $this->lecture
    //             ->where('lecture_id', $data->lecture_id)
    //             ->update([
    //                 'payed_user_id' => $data->user_id,
    //                 'tokbox_session_id' => $this->tokbox->create_session()
    //             ]);

    //         return $flag_1 && $flag_2;
    //     }

    //     return $payment_response;
    // }

    public function booked(Order $order)
    {
        $lectures = json_decode($order->lectures, 'Array');
        $lectures_array = [];
        foreach($lectures as $lecture){
            $lectures_array['lecture_id'] = $lecture['lecture_id'];
        }

        $lectures_ids = array_values($lectures_array);

        $userId = \Auth::user()->id;
        
        $flag_1 = $this->schedule
            ->whereIn('lecture_id', $lectures_ids)
            ->where('user_id', $userId)
            ->update(['payed' => true, 'status' => 'success']);

         //delete others
        $this->schedule
            ->whereIn('lecture_id', $lectures_ids)
            ->where('user_id', '!=', $userId)
            ->delete();

        $flag_2 = $this->lecture
            ->whereIn('id', $lectures_ids)
            ->update([
                'payed_user_id' => $userId,
                'tokbox_session_id' => $this->tokbox->create_session()
            ]);

        $order->update(['status' => 'success']);

        return $flag_1 && $flag_2;
    }


    public function bookedFawry(Order $order)
    {
        $lectures = json_decode($order->lectures, 'Array');
        $lectures_array = [];
        foreach($lectures as $lecture){
            $lectures_array['lecture_id'] = $lecture['lecture_id'];
        }

        $lectures_ids = array_values($lectures_array);

        $userId = \Auth::user()->id;

        $flag_1 = $this->schedule
        ->whereIn('lecture_id', $lectures_ids)
        ->where('user_id', $userId)
        ->update(['status' => 'pending']);

         //delete others
        $this->schedule
            ->whereIn('lecture_id', $lectures_ids)
            ->where('user_id', '!=', $userId)
            ->delete();

        $flag_2 = $this->lecture
            ->whereIn('id', $lectures_ids)
            ->update([
                'payed_user_id' => $userId,
                'tokbox_session_id' => $this->tokbox->create_session()
            ]);

        return $flag_1 && $flag_2;
    }
*/
    public function getSessionForUserWithPaginate($user_id, $date, $operator = '>=')
    {
        return $this->schedule
            ->where('user_id', $user_id)
            ->where('payed', '=', 1)
            ->whereRaw('CONCAT(`date`," ",`time_to`) ' . $operator . '"' . \Carbon\Carbon::parse($date)->format("Y-m-d H:i:s") . '"')
            // ->where('time_to',$operator,\Carbon\Carbon::parse($date)->format('H:i'))
            ->with('teachers')
            ->paginate($this->number_of_pages);
    }
    public function getSessionForTeacherWithPaginate($user_id, $date, $operator = '>=')
    {
        return $this->schedule
            ->where('teacher_id', $user_id)
            ->where('payed', '=', 1)
            ->whereRaw('CONCAT(`date`," ",`time_to`) ' . $operator . '"' . \Carbon\Carbon::parse($date)->format("Y-m-d H:i:s") . '"')
            // ->where('time_to',$operator,\Carbon\Carbon::parse($date)->format('H:i'))
            ->with('teachers')
            ->paginate($this->number_of_pages);
    }
    public function getPastSessionForTeacherWithPaginate($user_id)
    {
        return $this->getSessionForTeacherWithPaginate($user_id, now(), "<");
    }
    public function getComingSessionForTeacherWithPaginate($user_id)
    {
        return $this->getSessionForTeacherWithPaginate($user_id, now(), ">=");
    }

    public function getPastSessionForUserWithPaginate($user_id)
    {
        return $this->getSessionForUserWithPaginate($user_id, now(), "<");
    }
    public function getComingSessionForUserWithPaginate($user_id)
    {
        return $this->getSessionForUserWithPaginate($user_id, now(), ">=");
    }

    public function getSessionForSubUserWithPaginate($sub_user_id, $user_id, $date, $operator = '>=')
    {
        return $this->schedule
            ->where('payed', '=', 1)
            ->where('user_id', $user_id)
            ->where('sub_user_id', $sub_user_id)
            ->whereRaw('CONCAT(`date`," ",`time_to`) ' . $operator . '"' . \Carbon\Carbon::parse($date)->format("Y-m-d H:i:s") . '"')
           // ->where('time_to',$operator,\Carbon\Carbon::parse($date)->format('H:i'))
            ->with('teachers')
            ->paginate($this->number_of_pages);
    }

    public function getPastSessionForSubUserWithPaginate($sub_user_id, $user_id)
    {
        return $this->getSessionForSubUserWithPaginate($sub_user_id, $user_id, now(), "<");
    }
    public function getComingSessionForSubUserWithPaginate($sub_user_id, $user_id)
    {
        return $this->getSessionForSubUserWithPaginate($sub_user_id, $user_id, now(), ">=");
    }
} 
