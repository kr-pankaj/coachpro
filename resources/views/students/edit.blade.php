<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('students.update', $student) }}">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $student->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Phone')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $student->phone)" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="parent_phone" :value="__('Parent Phone')" />
                                <x-text-input id="parent_phone" class="block mt-1 w-full" type="text" name="parent_phone" :value="old('parent_phone', $student->parent_phone)" />
                                <x-input-error :messages="$errors->get('parent_phone')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="batch_id" :value="__('Batch')" />
                                <select id="batch_id" name="batch_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Select Batch</option>
                                    @foreach($batches as $batch)
                                        <option value="{{ $batch->id }}" {{ old('batch_id', $student->batch_id) == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('batch_id')" class="mt-2" />
                            </div>

                            <div class="sm:col-span-2">
                                <x-input-label for="address" :value="__('Address')" />
                                <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old('address', $student->address) }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="joined_date" :value="__('Joined Date')" />
                                <x-text-input id="joined_date" class="block mt-1 w-full" type="date" name="joined_date" :value="old('joined_date', $student->joined_date)" />
                                <x-input-error :messages="$errors->get('joined_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Student') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
