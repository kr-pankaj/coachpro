<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Quiz Results: {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Quiz Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                    <div><p class="text-2xl font-black text-indigo-600">{{ $quiz->questions_count }}</p><p class="text-xs text-gray-500 mt-1">Questions</p></div>
                    <div><p class="text-2xl font-black text-gray-800">{{ $quiz->time_limit_minutes }}m</p><p class="text-xs text-gray-500 mt-1">Time Limit</p></div>
                    <div><p class="text-2xl font-black text-green-600">{{ $attempts->whereNotNull('completed_at')->count() }}</p><p class="text-xs text-gray-500 mt-1">Completed</p></div>
                    <div><p class="text-2xl font-black text-amber-600">{{ $attempts->whereNotNull('completed_at')->avg('score') ? round($attempts->whereNotNull('completed_at')->avg('score'), 1) : 'N/A' }}</p><p class="text-xs text-gray-500 mt-1">Avg. Score</p></div>
                </div>
            </div>

            <!-- Student Attempts Table -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100"><h3 class="font-bold text-gray-800 dark:text-gray-200">Student Attempts</h3></div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Student</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Score</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Percentage</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Completed At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($attempts->whereNotNull('completed_at') as $attempt)
                                @php $pct = $attempt->total_marks > 0 ? round(($attempt->score / $attempt->total_marks) * 100) : 0; @endphp
                                <tr>
                                    <td class="px-5 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $attempt->student?->name ?? 'Unknown' }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-600">{{ $attempt->score }} / {{ $attempt->total_marks }}</td>
                                    <td class="px-5 py-3">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $pct >= 60 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $pct }}%</span>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-500">{{ $attempt->completed_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">No students have completed this quiz yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
