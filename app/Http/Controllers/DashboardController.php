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
                ->where('type', 'debit') // Filter by type (assuming 'debit' indicates debit expenses)
                ->where('expense_category_id', $category->id) // Use expense_category_id
                ->sum('amount'); // Sum the amounts for each category
        })->toArray();

        return view('dashboard.index', compact('lineChartLabels', 'lineChartData', 'pieChartLabels', 'pieChartData'));
    }

    public function fetchData(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month');
        $year = $request->input('year');

        // Get only debited expenses for the selected month and year
        $expenses = Expense::where('user_id', $user->id)
            ->where('type', 'debit')
            ->whereMonth('tracked_date', $month)
            ->whereYear('tracked_date', $year)
            ->get();

        // Prepare data for the line chart
        $lineChartLabels = [];
        $lineChartData = [];

        foreach ($expenses as $expense) {
            $date = $expense->tracked_date->format('Y-m-d');
            if (!isset($lineChartData[$date])) {
                $lineChartData[$date] = 0;
                $lineChartLabels[] = $date;
            }
            $lineChartData[$date] += $expense->amount; // Sum the debited amounts
        }

        // Get categories for the pie chart
        $categories = ExpenseCategory::all();

        $pieChartLabels = $categories->pluck('name')->toArray();
        $pieChartData = $categories->map(function ($category) use ($user, $month, $year) {
            return Expense::where('user_id', $user->id)
                ->where('type', 'debit')
                ->where('expense_category_id', $category->id)
                ->whereMonth('tracked_date', $month)
                ->whereYear('tracked_date', $year)
                ->sum('amount');
        })->toArray();

        return response()->json([
            'lineChartLabels' => $lineChartLabels,
            'lineChartData' => array_values($lineChartData),
            'pieChartLabels' => $pieChartLabels,
            'pieChartData' => $pieChartData,
        ]);
    }
}
