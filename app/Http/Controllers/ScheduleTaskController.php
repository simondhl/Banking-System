<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleTaskFormRequest;
use App\Http\Requests\TransactionFormRequest;
use App\Services\ScheduleTaskService;
use App\Services\TransactionService;

class ScheduleTaskController extends Controller
{
    protected ScheduleTaskService $scheduleTaskService;

    public function __construct(ScheduleTaskService $scheduleTaskService)
    {
        $this->scheduleTaskService = $scheduleTaskService;
    }

    public function deposit_or_withdrawal_schedule(ScheduleTaskFormRequest $request)
    {
        $result = $this->scheduleTaskService->deposit_or_withdrawal_schedule($request->validated());

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function transfer_schedule(ScheduleTaskFormRequest $request)
    {
        $result = $this->scheduleTaskService->transfer_schedule($request->validated());

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
