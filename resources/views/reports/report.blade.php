<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Banking System Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        h2 {
            text-align: center;
            font-weight: normal;
            margin-bottom: 30px;
        }

        .section-title {
            background-color: #2c3e50;
            color: #fff;
            padding: 8px;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #ecf0f1;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .empty-row {
            text-align: center;
            font-style: italic;
            color: #777;
        }

        .summary-box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-top: 20px;
            width: 60%;
        }

        .no-data {
            text-align: center;
            margin-top: 50px;
            font-size: 16px;
            color: #555;
        }

        footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    <h1>📄 Banking System Report</h1>
    <h2>From {{ $start_date }} to {{ $end_date }}</h2>

    @if($transactions->isEmpty() && $accounts->isEmpty() && $inquiries_count === 0)
        <div class="no-data">
            No data available for the selected period.
        </div>
    @endif

    <div class="section-title">Transactions</div>

    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ ucfirst($transaction->type) }}</td>
                    <td>{{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="empty-row">
                        No transactions found for this period.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Transaction Summary by Type</div>

    <table style="width: 60%;">
        <thead>
            <tr>
                <th>Transaction Type</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Deposit</td>
                <td>{{ number_format($transaction_totals['deposit'] ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>Withdrawal</td>
                <td>{{ number_format($transaction_totals['withdrawal'] ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>Transfer</td>
                <td>{{ number_format($transaction_totals['transfer'] ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title page-break">Accounts</div>

    <table>
        <thead>
            <tr>
                <th>Account Number</th>
                <th>Balance</th>
                <th>Account Type</th>
                <th>Hierarchy</th>
                <th>Status</th>
                <th>Parent Account Number</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $account)
                <tr>
                    <td>{{ $account['account_number'] }}</td>
                    <td>{{ number_format($account['balance'], 2) }}</td>
                    <td>{{ $account['account_type'] ?? '-' }}</td>
                    <td>{{ $account['account_hierarchy'] ?? '-' }}</td>
                    <td>{{ $account['account_status'] ?? '-' }}</td>
                    <td>{{ $account['parent_account_number'] ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($account['created_at'])->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-row">
                        No accounts found for this period.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">General Summary</div>

    <div class="summary-box">
        <p>Total Transactions: <strong>{{ $transactions->count() }}</strong></p>
        <p>Total Accounts: <strong>{{ $accounts->count() }}</strong></p>
        <p>Total Inquiries: <strong>{{ $inquiries_count }}</strong></p>
    </div>

    <footer>
        This report was generated automatically by the banking system.
    </footer>

</body>
</html>
