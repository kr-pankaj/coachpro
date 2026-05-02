<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Create New Quiz</h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('quizzes.store') }}" id="quiz-form">
                @csrf

                <!-- Quiz Details -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">Quiz Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Quiz Title *</label>
                            <input type="text" name="title" value="{{ old('title') }}" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" placeholder="e.g. Chapter 3 Test – Motion">
                            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Assign to Batch</label>
                            <select name="batch_id" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">
                                <option value="">-- All Batches --</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Time Limit (Minutes) *</label>
                            <input type="number" name="time_limit_minutes" value="{{ old('time_limit_minutes', 30) }}" min="1" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">
                        </div>
                        <div class="md:col-span-2 flex items-center gap-3">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <label for="is_active" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Publish Quiz (visible to students immediately)</label>
                        </div>
                    </div>
                </div>

                <!-- Questions Builder -->
                <div id="questions-container">
                    <div class="question-block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-4" data-index="0">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-gray-800 dark:text-gray-200">Question 1</h4>
                            <button type="button" onclick="removeQuestion(this)" class="text-sm text-red-500 hover:text-red-700 hidden">Remove</button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Question Text *</label>
                                <textarea name="questions[0][question_text]" rows="2" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm resize-none" placeholder="e.g. What is Newton's first law of motion?"></textarea>
                            </div>
                            <div class="w-28">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Marks *</label>
                                <input type="number" name="questions[0][marks]" value="1" min="1" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Options (select the correct one) *</label>
                                <div class="options-list space-y-2">
                                    @for($o = 0; $o < 4; $o++)
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="questions[0][correct_option]" value="{{ $o }}" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ $o == 0 ? 'required' : '' }}>
                                        <input type="text" name="questions[0][options][{{ $o }}][option_text]" required placeholder="Option {{ chr(65+$o) }}" class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addQuestion()" class="w-full flex items-center justify-center gap-2 py-3 border-2 border-dashed border-indigo-300 text-indigo-600 rounded-2xl font-semibold hover:bg-indigo-50 transition-colors mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Another Question
                </button>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('quizzes.index') }}" class="px-5 py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-900">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white rounded-lg" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">Publish Quiz</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    let questionCount = 1;

    function addQuestion() {
        const container = document.getElementById('questions-container');
        const idx = questionCount++;
        const block = document.createElement('div');
        block.className = 'question-block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-4';
        block.setAttribute('data-index', idx);
        const options = [0,1,2,3].map(o => `
            <div class="flex items-center gap-2">
                <input type="radio" name="questions[${idx}][correct_option]" value="${o}" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" ${o==0?'required':''}>
                <input type="text" name="questions[${idx}][options][${o}][option_text]" required placeholder="Option ${String.fromCharCode(65+o)}" class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
            </div>`).join('');
        block.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-gray-800">Question ${idx + 1}</h4>
                <button type="button" onclick="removeQuestion(this)" class="text-sm text-red-500 hover:text-red-700">Remove</button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Question Text *</label>
                    <textarea name="questions[${idx}][question_text]" rows="2" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm resize-none" placeholder="Enter question here..."></textarea>
                </div>
                <div class="w-28">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Marks *</label>
                    <input type="number" name="questions[${idx}][marks]" value="1" min="1" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Options (select the correct one) *</label>
                    <div class="options-list space-y-2">${options}</div>
                </div>
            </div>`;
        container.appendChild(block);
        // Show remove buttons if more than 1 question
        document.querySelectorAll('.question-block button[onclick="removeQuestion(this)"]').forEach(b => b.classList.remove('hidden'));
    }

    function removeQuestion(btn) {
        const blocks = document.querySelectorAll('.question-block');
        if (blocks.length <= 1) return;
        btn.closest('.question-block').remove();
        // Re-number
        document.querySelectorAll('.question-block').forEach((b, i) => {
            b.querySelector('h4').textContent = `Question ${i + 1}`;
        });
        if (document.querySelectorAll('.question-block').length <= 1) {
            document.querySelectorAll('.question-block button[onclick="removeQuestion(this)"]').forEach(b => b.classList.add('hidden'));
        }
    }
    </script>
</x-app-layout>
