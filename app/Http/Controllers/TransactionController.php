<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\Expense;
use App\Models\Incentive;
use App\Models\Lifting;
use App\Models\Payment;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        $rows = new Collection();

        DailySale::whereBetween('sale_date', [$from, $to])
            ->selectRaw('sale_date, sales_rep_id, SUM(net_amount) as total')
            ->groupBy('sale_date', 'sales_rep_id')
            ->with('salesRep')
            ->get()
            ->each(function ($r) use ($rows) {
                if ($r->total > 0) {
                    $rows->push([
                        'date' => $r->sale_date,
                        'type' => 'Sale',
                        'description' => 'Sale to '.($r->salesRep?->name ?? 'SR'),
                        'direction' => 'in',
                        'amount' => $r->total,
                    ]);
                }
            });

        Lifting::whereBetween('lifting_date', [$from, $to])
            ->selectRaw('lifting_date, supplier_id, SUM(value) as total')
            ->groupBy('lifting_date', 'supplier_id')
            ->with('supplier')
            ->get()
            ->each(function ($r) use ($rows) {
                if ($r->total > 0) {
                    $rows->push([
                        'date' => $r->lifting_date,
                        'type' => 'Purchase',
                        'description' => 'Lifting from '.($r->supplier?->name ?? 'Supplier'),
                        'direction' => 'out',
                        'amount' => $r->total,
                    ]);
                }
            });

        SupplierPayment::whereBetween('payment_date', [$from, $to])->with('supplier')->get()->each(function ($p) use ($rows) {
            $rows->push([
                'date' => $p->payment_date,
                'type' => 'Supplier Payment',
                'description' => 'Paid to '.($p->supplier?->name ?? 'Supplier').($p->note ? ' — '.$p->note : ''),
                'direction' => 'out',
                'amount' => $p->amount,
            ]);
        });

        Expense::whereBetween('expense_date', [$from, $to])->get()->each(function ($e) use ($rows) {
            $rows->push([
                'date' => $e->expense_date,
                'type' => 'Expense',
                'description' => $e->category.($e->description ? ' — '.$e->description : ''),
                'direction' => 'out',
                'amount' => $e->amount,
            ]);
        });

        Incentive::whereBetween('incentive_date', [$from, $to])->with('salesRep')->get()->each(function ($i) use ($rows) {
            $rows->push([
                'date' => $i->incentive_date,
                'type' => 'Incentive',
                'description' => 'Incentive to '.($i->salesRep?->name ?? 'SR'),
                'direction' => 'out',
                'amount' => $i->amount,
            ]);
        });

        Payment::whereBetween('payment_date', [$from, $to])->get()->each(function ($p) use ($rows) {
            $rows->push([
                'date' => $p->payment_date,
                'type' => 'Payment',
                'description' => ($p->party_name ?? 'Payment').($p->note ? ' — '.$p->note : ''),
                'direction' => $p->direction,
                'amount' => $p->amount,
            ]);
        });

        $sorted = $rows->sortByDesc('date')->values();

        $totalIn = $sorted->where('direction', 'in')->sum('amount');
        $totalOut = $sorted->where('direction', 'out')->sum('amount');

        return view('transactions.index', [
            'rows' => $sorted,
            'from' => $from,
            'to' => $to,
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
        ]);
    }
}
