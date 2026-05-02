<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile Update Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Requested Changes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($requests as $req)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $req->student->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <ul class="list-disc ml-4">
                                            @foreach($req->requested_changes as $key => $value)
                                                <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $req->created_at->format('M d, Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                        <form action="{{ route('profile_requests.update', $req) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 bg-green-50 dark:bg-green-900/30 px-3 py-1 rounded">Approve</button>
                                        </form>
                                        <form action="{{ route('profile_requests.update', $req) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/30 px-3 py-1 rounded">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">No pending profile update requests.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
