<x-app-layout>
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    @endpush
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('students.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email (Required for Portal access) *')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Phone *')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="parent_phone" :value="__('Parent Phone')" />
                                <x-text-input id="parent_phone" class="block mt-1 w-full" type="text" name="parent_phone" :value="old('parent_phone')" />
                                <x-input-error :messages="$errors->get('parent_phone')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="batch_ids" :value="__('Enrolled Batches *')" />
                                <select id="batch_ids" name="batch_ids[]" class="block mt-1 w-full" multiple required>
                                    @foreach($batches as $batch)
                                        <option value="{{ $batch->id }}" {{ (is_array(old('batch_ids')) && in_array($batch->id, old('batch_ids'))) ? 'selected' : '' }}>
                                            {{ $batch->name }} ({{ $batch->subject }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('batch_ids')" class="mt-2" />
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    new TomSelect('#batch_ids', {
                                        plugins: ['remove_button'],
                                        maxItems: 10,
                                        placeholder: 'Select one or more batches...',
                                        render: {
                                            option: function(data, escape) {
                                                return '<div><span class="font-bold">' + escape(data.text) + '</span></div>';
                                            },
                                            item: function(data, escape) {
                                                return '<div>' + escape(data.text) + '</div>';
                                            }
                                        }
                                    });
                                });
                            </script>

                            <div class="sm:col-span-2">
                                <x-input-label for="address" :value="__('Address')" />
                                <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old('address') }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="joined_date" :value="__('Joined Date')" />
                                <x-text-input id="joined_date" class="block mt-1 w-full" type="date" name="joined_date" :value="old('joined_date', now()->toDateString())" />
                                <x-input-error :messages="$errors->get('joined_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Create Student') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
