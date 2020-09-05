<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class NotificationController extends Controller
{
    public function getAll()
    {   $user=auth()->user();
        $Notifications = $user->notifications()->skip(0)->take(50)->get();
       
        $user->unreadNotifications->markAsRead();

        return response()->json(['notifications' =>  $Notifications], 200);
    }
}
