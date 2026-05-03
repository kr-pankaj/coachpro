<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Batch') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('batches.update', $batch) }}">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $batch->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="subject" :value="__('Subject')" />
                            <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject', $batch->subject)" />
                            <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="time_slot" :value="__('Time Slot')" />
                            <x-text-input id="time_slot" class="block mt-1 w-full" type="text" name="time_slot" :value="old('time_slot', $batch->time_slot)" placeholder="e.g. 10:00 AM - 12:00 PM" />
                            <x-input-error :messages="$errors->get('time_slot')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="teacher_id" :value="__('Assign Teacher')" />
                            <select id="teacher_id" name="teacher_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- No Teacher Assigned --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $batch->teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('teacher_id')" class="mt-2" />
                        </div>

                        <div class="mt-4 flex items-center gap-2">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $batch->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <x-input-label for="is_active" :value="__('This batch is currently active')" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Batch') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
