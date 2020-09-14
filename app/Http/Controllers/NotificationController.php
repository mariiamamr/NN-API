<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class NotificationController extends Controller
{
    /**
     * 
     * @group  Notifications
     * 
     * used to get the notification history for the currently logged in user.
     *  
     * @authenticated
     * @response 200{
     *    "notifications": [
     *       {
     *           "id": "79947688-9376-4b3f-95fd-6e3f4c31c1f4",
     *           "type": "App\\Notifications\\ReportNotification",
     *           "notifiable_type": "App\\User",
     *           "notifiable_id": 17,
     *           "data": {
     *               "message": "Your report has been submitted successfully"
     *           },
     *          "read_at": null,
     *           "created_at": "2020-09-14 19:27:31",
     *           "updated_at": "2020-09-14 19:27:31"
     *       },
     *       {
     *           "id": "66354ef2-9168-4efc-a13d-942cef01ab87",
     *           "type": "App\\Notifications\\Admin\\AdminNotification",
     *           "notifiable_type": "App\\User",
     *           "notifiable_id": 17,
     *           "data": {
     *              "message": "Price approval of teacher needing for approval!"
     *           },
     *           "read_at": null,
     *           "created_at": "2020-09-08 19:10:14",
     *           "updated_at": "2020-09-08 19:10:14"
     *       },
     *       {
     *           "id": "c5bbb766-cfa9-4b37-9a4e-bc144eb0298f",
     *           "type": "App\\Notifications\\Admin\\AdminNotification",
     *           "notifiable_type": "App\\User",
     *          "notifiable_id": 17,
     *           "data": {
     *               "message": "Price approval of teacher needing for approval!"
     *           },
     *           "read_at": null,
     *           "created_at": "2020-09-08 19:06:49",
     *           "updated_at": "2020-09-08 19:06:49"
     *       },
     *       {
     *           "id": "23ff3d46-bc8f-47cf-9f60-c3c72682c2db",
     *           "type": "App\\Notifications\\BookSessionStudent",
     *          "notifiable_type": "App\\User",
     *           "notifiable_id": 17,
     *            "data": {
     *               "message": "Dear wrcefc:\r\n      This is to confirm that you have booked a session:2020-10-20 at 04:00:00\r\n"
     *           },
     *           "read_at": null,
     *           "created_at": "2020-09-02 14:43:50",
     *           "updated_at": "2020-09-02 14:43:50"
     *       },
     *       {
     *           "id": "07706623-f57b-4d60-95b1-d8e93dbc0093",
     *          "type": "App\\Notifications\\BookSession",
     *           "notifiable_type": "App\\User",
     *           "notifiable_id": 17,
     *           "data": {
     *               "message": "Dear wrcefc:\r\n      wrcefc has booked a session with you:\r\n      2020-10-20 at 04:00:00\r\n"
     *           },
     *          "read_at": null,
     *           "created_at": "2020-09-02 14:43:48",
     *           "updated_at": "2020-09-02 14:43:48"
     *       },
     *       {
     *           "id": "3d346d9f-0d81-4f70-ac8d-94aa6f9207c2",
     *           "type": "App\\Notifications\\ReportNotification",
     *           "notifiable_type": "App\\User",
     *           "notifiable_id": 17,
     *           "data": {
     *               "message": "Your report has been submitted successfully"
     *           },
     *           "read_at": null,
     *          "created_at": "2020-09-01 14:46:52",
     *           "updated_at": "2020-09-01 14:46:52"
     *       },
     *       {
     *           "id": "e40a4875-2ad6-4ea4-aa1e-49e78f6b29a8",
     *           "type": "App\\Notifications\\ReportNotification",
     *           "notifiable_type": "App\\User",
     *           "notifiable_id": 17,
     *           "data": {
     *              "message": "Your report has been submitted successfully"
     *           },
     *           "read_at": null,
     *           "created_at": "2020-09-01 14:34:11",
     *           "updated_at": "2020-09-01 14:34:11"
     *       },
     *       {
     *           "id": "097f3495-b9b3-456f-9f9b-5b5aae0a0f7f",
     *           "type": "App\\Notifications\\RatingNewTeacher",
     *           "notifiable_type": "App\\User",
     *           "notifiable_id": 17,
     *           "data": {
     *               "student_id": 18,
     *               "student_name": "wrcefc",
     *               "student_image": null,
     *               "session_date": "2020-10-20",
     *               "session_time": "04:00:00"
     *           },
     *           "read_at": null,
     *           "created_at": "2020-08-27 18:50:42",
     *           "updated_at": "2020-08-27 18:50:42"
     *       }
     *   ]
     * }
     */

    public function getAll()
    {   $user=auth()->user();
        $Notifications = $user->notifications()->skip(0)->take(50)->get();
       
        $user->unreadNotifications->markAsRead();

        return response()->json(['notifications' =>  $Notifications], 200);
    }
}
