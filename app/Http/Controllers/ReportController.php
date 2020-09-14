<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Container\Contracts\Reports\ReportContract;

class ReportController extends Controller
{

    protected $report;
  
    public function __construct( ReportContract $report)
      {
        $this->report = $report;
      }
   /**
     * Report teacher
     * @group  Report
     * 
     * used to report a teacher
     *  
     *
     * @bodyParam content string required the report message.
     * @bodyParam teacher_id integer required the ID of the teacher to be reported.
     * @response 200{
     *  "message": {
     *   "email": "ayaelsac.1999@gmail.com",
     *   "content": "byhjn",
     *   "user_id": 17,
     *   "teacher_id": 20,
     *   "updated_at": "2020-09-14 19:27:06",
     *   "created_at": "2020-09-14 19:27:06",
     *   "id": 4
     *}
     * 
     */

    public function report(Request $request)
    {
        $report = $this->report->set(\Auth::id(), $request);
        return response()->json([
            'report'=>   $report 
        ], 200);
    }
}
