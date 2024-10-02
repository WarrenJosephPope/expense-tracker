@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Expense</h1>

    <form action="{{ route('expenses.update', $expense->id) }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Expense Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $expense->name) }}" class="border border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200 w-full px-3 py-2 @error('name') border-red-500 @enderror">
            @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type</label>
            <select name="type" id="type" class="border border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200 w-full px-3 py-2 @error('type') border-red-500 @enderror">
                <option value="debit" {{ $expense->type === 'debit' ? 'selected' : '' }}>Debit</option>
                <option value="credit" {{ $expense->type === 'credit' ? 'selected' : '' }}>Credit</option>
            </select>
            @error('type')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount</label>
            <input type="number" name="amount" id="amount" value="{{ old('amount', $expense->amount) }}" class="border border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200 w-full px-3 py-2 @error('amount') border-red-500 @enderror">
            @error('amount')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="tracked_date" class="block text-gray-700 text-sm font-bold mb-2">Tracked Date</label>
            <input type="date" name="tracked_date" id="tracked_date" value="{{ old('tracked_date', $expense->tracked_date) }}" class="border border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200 w-full px-3 py-2 @error('tracked_date') border-red-500 @enderror">
            @error('tracked_date')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="expense_category_id" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
            <select name="expense_category_id" id="expense_category_id" class="border border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200 w-full px-3 py-2 @error('expense_category_id') border-red-500 @enderror">
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $expense->expense_category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
            @error('expense_category_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-gray-300">Update Expense</button>
            <a href="{{ route('expenses.index') }}" class="text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection