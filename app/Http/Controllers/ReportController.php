<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function report(Request $request)
    {
        $report = $this->report->set(\Auth::id(), $request);
        return response()->json([
            'message'=>   $report 
        ], 200);
    }
}
