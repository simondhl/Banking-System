<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportFormRequest;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportService $reportService;
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function get_report_by_date(ReportFormRequest $request){
        $result = $this->reportService->get_report_by_date($request->validated());
        return $result;
    }
}
