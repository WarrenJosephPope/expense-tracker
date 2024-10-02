<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    // Display a listing of expenses
    public function index(Request $request)
    {
        $user = auth()->user();

        $balance = $user->balance;

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Expense::with('category')->where('user_id', Auth::id());

        // Apply filters based on start and end date if provided
        if ($startDate && $endDate) {
            $query->whereBetween('tracked_date', [$startDate, $endDate]);
        }

        // Apply filters based on request
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->input('category_id'));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $expenses = $query->paginate(10);
        $categories = ExpenseCategory::all();

        return view('expenses.index', compact('expenses', 'categories', 'balance'));
    }

    // Show the form for creating a new expense
    public function create()
    {
        $categories = ExpenseCategory::all();
        return view('expenses.create', compact('categories'));
    }

    // Store a newly created expense in storage
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:0',
            'tracked_date' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
        ]);

        // Create the expense
        $expense = Expense::create([
            'name' => $request->name,
            'type' => $request->type,
            'amount' => $request->amount,
            'tracked_date' => $request->tracked_date,
            'expense_category_id' => $request->expense_category_id,
            'user_id' => Auth::id(), // Associate expense with the authenticated user
        ]);

        // Update user balance based on expense type
        $this->updateUserBalance($expense);

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    // Show the form for editing the specified expense
    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::all();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    // Update the specified expense in storage
    public function update(Request $request, Expense $expense)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0',
            'tracked_date' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
        ]);

        // Find the user
        $user = $request->user();

        // Determine the old amount before update
        $oldAmount = $expense->amount;
        $oldType = $expense->type;

        // Update the expense
        $expense->update($request->only('name', 'type', 'amount', 'tracked_date', 'expense_category_id'));

        // Adjust user's money
        if ($oldType === 'debit') {
            $user->money += $oldAmount; // Revert old amount
        } else {
            $user->money -= $oldAmount; // Revert old amount
        }

        if ($request->type === 'debit') {
            $user->money -= $request->amount; // Deduct for new debit
        } else {
            $user->money += $request->amount; // Add for new credit
        }

        // Save the updated user's money
        $user->save();

        // Redirect back with success message
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully!');
    }


    // Remove the specified expense from storage
    public function destroy(Request $request, Expense $expense)
    {
        // Find the user
        $user = $request->user();

        // Adjust user's money before deleting
        if ($expense->type === 'debit') {
            $user->money += $expense->amount; // Add back the amount for debit
        } else {
            $user->money -= $expense->amount; // Deduct the amount for credit
        }

        // Save the updated user's money
        $user->save();

        // Delete the expense
        $expense->delete();

        // Redirect back with success message
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully!');
    }


    // Helper method to update user balance based on the expense type
    protected function updateUserBalance(Expense $expense)
    {
        $user = Auth::user();
        if ($expense->type === 'debit') {
            $user->decrement('balance', $expense->amount);
        } else {
            $user->increment('balance', $expense->amount);
        }
        $user->save();
    }
}
