@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10">
    <h1 class="text-2xl font-semibold mb-6">Expense Categories</h1>

    <!-- Create Category Button -->
    <a href="{{ route('expense-categories.create') }}" class="mb-4 inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
        Create Expense Category
    </a>

    <!-- Search Form -->
    <form action="{{ route('expense-categories.index') }}" method="GET" class="mb-4 flex flex-col space-y-4 md:flex-row md:space-x-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name" class="block w-full md:w-1/4 border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring focus:ring-gray-200">
        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Search</button>
    </form>

    <!-- Categories Table -->
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Name</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td class="py-2 px-4 border-b">{{ $category->name }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('expense-categories.edit', $category->id) }}" class="text-blue-600 hover:text-blue-700">Edit</a>
                    <form action="{{ route('expense-categories.destroy', $category->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center py-3">No categories found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    {{ $categories->links() }}
</div>
@endsection