<?php

namespace App\Http\Controllers;

use App\Http\Requests\InquiryFormRequest;
use App\Services\InquiryService;

class InquiryController extends Controller
{
    protected InquiryService $inquiryService;

    public function __construct(InquiryService $inquiryService)
    {
        $this->inquiryService = $inquiryService;
    }
    public function send_inquiry(InquiryFormRequest $request)
    {
        $result = $this->inquiryService->send_inquiry($request->validated());
        if (!$result) {
            return response()->json(['message' => 'Failed to send inquiry'], 400);
        }
        return response()->json($result, 200);
    }
    public function get_inquiries(){
        $result = $this->inquiryService->get_inquiries();
        return response()->json($result, 200);
    }
}
