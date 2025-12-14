<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Inquiry;
use App\Models\Transaction;
use App\Transformer\AccountTransformer;
use Barryvdh\DomPDF\PDF as DomPDF;

class ReportService
{
    public function get_report_by_date(array $request)
    {
        $transactions = Transaction::select('type', 'amount', 'created_at')
            ->whereDate('created_at', '>=', $request['start_date'])
            ->whereDate('created_at', '<=', $request['end_date'])
            ->get();
        $transaction_totals = $transactions->groupBy('type')->map(
            fn($group) =>
            $group->sum('amount')
        );
        $accounts = Account::with(['account_type', 'account_hierarchy', 'account_status'])
            ->whereDate('created_at', '>=', $request['start_date'])
            ->whereDate('created_at', '<=', $request['end_date'])
            ->get()
            ->map(fn($account) => AccountTransformer::transform($account));

        $inquiries_count = Inquiry::whereDate('created_at', '>=', $request['start_date'])
            ->whereDate('created_at', '<=', $request['end_date'])
            ->count();

        $pdf = app(DomPDF::class)->loadView('reports.report', [
            'transactions' => $transactions,
            'transaction_totals' => $transaction_totals,
            'accounts' => $accounts,
            'inquiries_count' => $inquiries_count,
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
        ])->setPaper('a4', 'landscape');

        return $pdf->download('report.pdf');
    }
}
