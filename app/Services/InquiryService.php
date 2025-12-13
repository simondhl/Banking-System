<?php

namespace App\Services;

use App\Models\Inquiry;

class InquiryService
{

    public function send_inquiry(array $request)
    {
        $userId = auth()->user()->id;
        $inquiry = Inquiry::create([
            'user_id' => $userId,
            'title' => $request['title'],
            'description' => $request['description'],
        ]);
        if (!$inquiry) {
            return false;
        }
        return [
            'message' => 'Inquiry sent successfully'
        ];
    }
    public function get_inquiries()
    {
        $inquiries = Inquiry::with('user')->get();
        if ($inquiries->isEmpty()) {
            return [
                'message' => 'There are no inquiries'
            ];
        }
        return [
            'inquiries' => $inquiries
        ];
    }
}
