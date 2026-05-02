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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <button type="button" onclick="addQuestion()" class="w-full flex items-center justify-center gap-2 py-4 border-2 border-dashed border-indigo-200 dark:border-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        Add Manual Question
                    </button>
                    <button type="button" onclick="openAIModal()" class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl font-black text-xs uppercase tracking-widest text-white shadow-lg shadow-violet-200 dark:shadow-none hover:scale-[1.02] active:scale-95 transition-all" style="background: linear-gradient(135deg, #8b5cf6, #6366f1);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Generate with AI ✨
                    </button>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('quizzes.index') }}" class="px-8 py-3 text-xs font-black uppercase tracking-widest text-gray-500 hover:text-gray-800 transition-all">Cancel</a>
                    <button type="submit" class="px-10 py-3 bg-gray-900 dark:bg-gray-200 text-white dark:text-gray-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-800 transition-all">Publish Quiz</button>
                </div>
            </form>
        </div>
    </div>

    <!-- AI Generator Modal -->
    <div id="aiModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500/75 backdrop-blur-sm" onclick="closeAIModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full sm:p-8">
                <div class="absolute top-0 right-0 pt-6 pr-6">
                    <button type="button" onclick="closeAIModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">AI Question Generator</h3>
                        </div>
                        <p class="text-sm text-gray-500 font-bold mb-6">Enter a topic or paste study material to generate high-quality MCQs instantly.</p>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 px-1">Topic or Source Text</label>
                                <textarea id="aiTopic" rows="5" class="w-full border-gray-100 dark:border-gray-700 dark:bg-gray-900 rounded-2xl p-4 text-sm focus:ring-violet-500 focus:border-violet-500 resize-none" placeholder="e.g. Photosynthesis process in green plants..."></textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 px-1">Number of Questions</label>
                                <select id="aiCount" class="w-full border-gray-100 dark:border-gray-700 dark:bg-gray-900 rounded-2xl p-4 text-sm focus:ring-violet-500 focus:border-violet-500">
                                    <option value="3">3 Questions</option>
                                    <option value="5" selected>5 Questions</option>
                                    <option value="10">10 Questions</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col gap-3">
                            <button type="button" id="generateAIBtn" onclick="generateAI()" class="w-full py-4 bg-gray-900 dark:bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:opacity-90 transition-all flex items-center justify-center gap-2">
                                <span id="btnText">Start Generation</span>
                                <svg id="spinner" class="w-4 h-4 animate-spin hidden" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                            <p class="text-center text-[10px] text-gray-400 font-bold italic">Powered by Gemini 1.5 Flash</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            </form>
        </div>
    </div>

    <script>
    let questionCount = 1;

    function openAIModal() {
        document.getElementById('aiModal').classList.remove('hidden');
    }

    function closeAIModal() {
        document.getElementById('aiModal').classList.add('hidden');
    }

    async function generateAI() {
        const topic = document.getElementById('aiTopic').value;
        const count = document.getElementById('aiCount').value;
        const btn = document.getElementById('generateAIBtn');
        const spinner = document.getElementById('spinner');
        const btnText = document.getElementById('btnText');

        if (!topic) {
            alert('Please enter a topic or some text first.');
            return;
        }

        // Loading state
        btn.disabled = true;
        spinner.classList.remove('hidden');
        btnText.textContent = 'Generating...';

        try {
            const response = await fetch('{{ route('ai.generate-questions') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ topic, count })
            });

            const data = await response.json();

            if (data.success) {
                // If the first question is empty, remove it
                const firstQuestionText = document.querySelector('textarea[name="questions[0][question_text]"]').value;
                if (!firstQuestionText && questionCount === 1) {
                    document.querySelector('.question-block').remove();
                    questionCount = 0;
                }

                data.questions.forEach(q => {
                    injectQuestion(q);
                });
                closeAIModal();
            } else {
                alert('AI Error: ' + data.message);
            }
        } catch (error) {
            alert('Error connecting to AI service.');
        } finally {
            btn.disabled = false;
            spinner.classList.add('hidden');
            btnText.textContent = 'Start Generation';
        }
    }

    function injectQuestion(qData) {
        const container = document.getElementById('questions-container');
        const idx = questionCount++;
        const block = document.createElement('div');
        block.className = 'question-block bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8 mb-6';
        block.setAttribute('data-index', idx);
        
        const options = qData.options.map((opt, o) => `
            <div class="flex items-center gap-3">
                <input type="radio" name="questions[${idx}][correct_option]" value="${o}" class="w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500" ${o == qData.correct_index ? 'checked' : ''} required>
                <input type="text" name="questions[${idx}][options][${o}][option_text]" value="${opt}" required class="flex-1 border-gray-100 dark:border-gray-700 dark:bg-gray-900 rounded-xl p-3 text-sm focus:ring-indigo-500">
            </div>`).join('');

        block.innerHTML = `
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Question ${idx + 1}</h4>
                <button type="button" onclick="removeQuestion(this)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                </button>
            </div>
            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 px-1">Question Text</label>
                    <textarea name="questions[${idx}][question_text]" rows="2" required class="w-full border-gray-100 dark:border-gray-700 dark:bg-gray-900 rounded-2xl p-4 text-sm focus:ring-indigo-500 resize-none">${qData.question}</textarea>
                </div>
                <div class="flex gap-6">
                    <div class="w-32">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 px-1">Marks</label>
                        <input type="number" name="questions[${idx}][marks]" value="1" min="1" required class="w-full border-gray-100 dark:border-gray-700 dark:bg-gray-900 rounded-2xl p-4 text-sm focus:ring-indigo-500">
                    </div>
                    <div class="flex-1">
                         <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 px-1">Explanation (AI)</label>
                         <input type="text" name="questions[${idx}][explanation]" value="${qData.explanation || ''}" class="w-full border-gray-100 dark:border-gray-700 dark:bg-gray-900 rounded-2xl p-4 text-sm focus:ring-indigo-500" placeholder="Optional explanation">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Options (Select Correct)</label>
                    <div class="space-y-3">${options}</div>
                </div>
            </div>`;
        container.appendChild(block);
        document.querySelectorAll('.question-block button[onclick="removeQuestion(this)"]').forEach(b => b.classList.remove('hidden'));
    }

    function addQuestion() {
        injectQuestion({
            question: '',
            options: ['', '', '', ''],
            correct_index: 0,
            explanation: ''
        });
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
