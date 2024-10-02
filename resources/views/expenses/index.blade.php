@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Expenses</h1>

    <!-- Filters Section -->
    <div class="flex flex-col md:flex-row mb-4 items-end flex-wrap">
        <form action="{{ route('expenses.index') }}" method="GET" class="flex items-end gap-2 mb-4 md:mb-0 flex-wrap">
            <!-- Start Date -->
            <div class="mb-4 md:mb-0">
                <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="border border-gray-300 rounded-md shadow-sm w-full px-3 py-2">
            </div>

            <!-- End Date -->
            <div class="mb-4 md:mb-0">
                <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="border border-gray-300 rounded-md shadow-sm w-full px-3 py-2">
            </div>

            <!-- Category Filter -->
            <div class="mb-4 md:mb-0">
                <label for="expense_category_id" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                <select name="expense_category_id" id="expense_category_id" class="border border-gray-300 rounded-md shadow-sm w-full px-3 py-2">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('expense_category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Type Filter -->
            <div class="mb-4 md:mb-0">
                <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type</label>
                <select name="type" id="type" class="border border-gray-300 rounded-md shadow-sm w-full px-3 py-2">
                    <option value="">All Types</option>
                    <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                    <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                </select>
            </div>

            <!-- Search Filter -->
            <div class="mb-4 md:mb-0">
                <label for="search" class="block text-gray-700 text-sm font-bold mb-2">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name" class="border border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200 px-3 py-2" />
            </div>

            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-gray-300">Filter</button>
        </form>

        <a href="{{ route('expenses.create') }}" class="ml-auto bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-gray-300">Create Expense</a>
    </div>

    <!-- Expenses Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr class="w-full bg-gray-200 text-gray-600 text-left">
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Type</th>
                    <th class="py-2 px-4 border-b">Amount</th>
                    <th class="py-2 px-4 border-b">Tracked Date</th>
                    <th class="py-2 px-4 border-b">Category</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($expenses as $expense)
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-2 px-4">{{ $expense->name }}</td>
                    <td class="py-2 px-4">{{ ucfirst($expense->type) }}</td>
                    <td class="py-2 px-4 {{ $expense->type === 'debit' ? 'text-red-500' : 'text-green-500' }}">
                        {{ $expense->type === 'debit' ? '-' : '+' }} ₹{{ number_format($expense->amount, 2) }}
                    </td>
                    <td class="py-2 px-4">{{ \Carbon\Carbon::parse($expense->tracked_date)->format('Y-m-d') }}</td>
                    <td class="py-2 px-4">{{ $expense->category->name ?? 'No Category' }}</td>
                    <td class="py-2 px-4">
                        <a href="{{ route('expenses.edit', $expense->id) }}" class="text-blue-500 hover:underline">Edit</a>
                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline ml-2" onclick="return confirm('Are you sure you want to delete this expense?');">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-4 text-center">No expenses found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $expenses->links() }}
    </div>
</div>
@endsection