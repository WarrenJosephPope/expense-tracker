@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Create Expense</h1>

    <form action="{{ route('expenses.store') }}" method="POST" class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Expense Name</label>
            <input type="text" name="name" id="name" required class="block w-full border border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200 px-3 py-2" placeholder="Enter expense name">
        </div>

        <div class="mb-4">
            <label for="type" class="block text-gray-700 text-sm font-semibold mb-2">Type</label>
            <select name="type" id="type" required class="block w-full border border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200 px-3 py-2">
                <option value="debit">Debit</option>
                <option value="credit">Credit</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="amount" class="block text-gray-700 text-sm font-semibold mb-2">Amount</label>
            <input type="number" name="amount" id="amount" required class="block w-full border border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200 px-3 py-2" placeholder="Enter amount">
        </div>

        <div class="mb-4">
            <label for="tracked_date" class="block text-gray-700 text-sm font-semibold mb-2">Tracked Date</label>
            <input type="date" name="tracked_date" id="tracked_date" required class="block w-full border border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200 px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="expense_category_id" class="block text-gray-700 text-sm font-semibold mb-2">Category</label>
            <select name="expense_category_id" id="expense_category_id" required class="block w-full border border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200 px-3 py-2">
                <option value="" disabled selected>Select Category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="mt-4 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-gray-300">Create</button>
        </div>
    </form>
</div>
@endsection