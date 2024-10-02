<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get expenses for the current month
        $expenses = Expense::where('user_id', $user->id)
            ->where('type', 'debit') // Filter by type (assuming 'debit' indicates debit expenses)
            ->whereMonth('tracked_date', now()->month) // Use tracked_date instead of created_at
            ->get();

        // Prepare data for the line chart
        $lineChartLabels = [];
        $lineChartData = [];

        foreach ($expenses as $expense) {
            $date = $expense->tracked_date->format('Y-m-d'); // Use tracked_date
            if (!isset($lineChartData[$date])) {
                $lineChartData[$date] = 0;
                $lineChartLabels[] = $date;
            }
            $lineChartData[$date] += $expense->amount; // Add the amount
        }

        // Get categories for the pie chart
        $categories = ExpenseCategory::all();

        $pieChartLabels = $categories->pluck('name')->toArray();
        $pieChartData = $categories->map(function ($category) use ($user) {
            return Expense::where('user_id', $user->id)
                ->where('expense_category_id', $category->id) // Use expense_category_id
                ->sum('amount'); // Sum the amounts for each category
        })->toArray();

        return view('dashboard.index', compact('lineChartLabels', 'lineChartData', 'pieChartLabels', 'pieChartData'));
    }

    public function getLineGraphData()
    {
        // For this month, group expenses by day
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // Replace with 'YEAR' for yearly data
        $expenses = Expense::where('user_id', auth()->id())
            ->whereBetween('tracked_date', [$startDate, $endDate])
            ->selectRaw('DATE(tracked_date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return response()->json($expenses);
    }

    public function getPieChartData()
    {
        // Total expenses per category
        $expenses = Expense::where('user_id', auth()->id())
            ->selectRaw('expense_category_id, SUM(amount) as total')
            ->groupBy('expense_category_id')
            ->with('category') // Ensure you fetch the category name
            ->get();

        return response()->json($expenses);
    }
}
