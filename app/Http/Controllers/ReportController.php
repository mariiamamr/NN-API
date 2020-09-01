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

    public function report(Request $request)
    {
        $report = $this->report->set(\Auth::id(), $request);
        return response()->json([
            'message'=>   $report 
        ], 200);
    }
}
