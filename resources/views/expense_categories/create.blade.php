@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Create Expense Category</h1>

    <form action="{{ route('expense-categories.store') }}" method="POST" class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Category Name</label>
            <input type="text" name="name" id="name" required class="block w-full border border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200 px-3 py-2" placeholder="Enter category name">
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="mt-4 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-gray-300">Create</button>
        </div>
    </form>
</div>
@endsection