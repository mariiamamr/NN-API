<?php

/**
 * Created by PhpStorm.
 * User: Backend Dev
 * Date: 5/17/2018
 * Time: 11:30 AM
 */

namespace App\Container\Contracts\Users;


use App\Models\Schedule;
use App\Models\Lecture;
use App\Models\Order;
//use Contracts\Payments\PaymentsContract;
//use Contracts\Services\TokBox\TokBoxContract;
//use Contracts\Payments\FawryContract;

interface UserEnrollsContract
{
    public function __construct(
        Schedule $schedule,
        Lecture $lecture,
        Order $order
        //PaymentsContract $payments,
        //TokBoxContract $tokbox,
        //FawryContract $fawry
    );

 /*   public function getPendingItems($user_id);
    public function getUnPaid($user_id);
    public function getPaid($user_id);
    public function enroll($user_id, $lecture, $data);
    public function checkout($user_id, $data);
    // public function booked($data);
    public function booked(Order $order);
    public function bookedFawry(Order $order);*/
    public function getSessionForUserWithPaginate($user_id, $date, $operator = '>=');
    public function getPastSessionForUserWithPaginate($user_id);
    public function getComingSessionForUserWithPaginate($user_id);
}
