<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('quizzes.index') }}" class="p-2 text-gray-400 hover:text-gray-600 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h2 class="font-black text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Quiz Settings</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-8 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">Quiz Fundamentals</h3>
                    <p class="text-sm text-gray-400 font-bold mt-1 uppercase tracking-widest">Update core settings for this assessment</p>
                </div>

                <form method="POST" action="{{ route('quizzes.update', $quiz) }}" class="p-8 space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 px-1">Quiz Title</label>
                            <input type="text" name="title" value="{{ old('title', $quiz->title) }}" required 
                                class="w-full border-gray-100 dark:border-gray-700 dark:bg-gray-900 rounded-2xl p-4 text-sm focus:ring-indigo-500 font-bold" 
                                placeholder="e.g. Mid-term Physics Test">
                            @error('title')<p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 px-1">Batch Assignment</label>
                            <select name="batch_id" class="w-full border-gray-100 dark:border-gray-700 dark:bg-gray-900 rounded-2xl p-4 text-sm focus:ring-indigo-500 font-bold">
                                <option value="">-- All Batches --</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" {{ old('batch_id', $quiz->batch_id) == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 px-1">Time Limit (Minutes)</label>
                                <input type="number" name="time_limit_minutes" value="{{ old('time_limit_minutes', $quiz->time_limit_minutes) }}" min="1" required 
                                    class="w-full border-gray-100 dark:border-gray-700 dark:bg-gray-900 rounded-2xl p-4 text-sm focus:ring-indigo-500 font-bold">
                            </div>
                            <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $quiz->is_active) ? 'checked' : '' }} 
                                    class="w-5 h-5 text-indigo-600 rounded-lg border-gray-300 focus:ring-indigo-500">
                                <label for="is_active" class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest">Active & Visible</label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 px-1">Description (Optional)</label>
                            <textarea name="description" rows="3" 
                                class="w-full border-gray-100 dark:border-gray-700 dark:bg-gray-900 rounded-2xl p-4 text-sm focus:ring-indigo-500 resize-none font-medium">{{ old('description', $quiz->description) }}</textarea>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-50 dark:border-gray-700 flex justify-between items-center">
                        <p class="text-[10px] text-gray-400 font-bold italic">* Note: Editing questions is coming soon.</p>
                        <div class="flex gap-3">
                            <a href="{{ route('quizzes.index') }}" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-gray-800 transition-all">Cancel</a>
                            <button type="submit" class="px-10 py-4 bg-gray-900 dark:bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-gray-200 dark:shadow-none hover:scale-[1.02] active:scale-95 transition-all">
                                Update Quiz
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
