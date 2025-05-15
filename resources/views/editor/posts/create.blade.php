<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create New Post') }}
            </h2>
            <a href="{{ route('editor.posts.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 text-sm">
                Back to Posts
            </a>
        </div>
    </x-slot>

    <x-slot name="styles">
        <x-head.tinymce-config />
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('editor.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Title Field -->
                        <div>
                            <x-input-label class="dark:text-gray-200" for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full dark:text-black" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Slug Field -->
                        <div>
                            <x-input-label class="dark:text-gray-200" for="slug" :value="__('Slug')" />
                            <div class="flex items-center">
                                <x-text-input id="slug" class="block mt-1 w-full dark:text-black" type="text" name="slug" :value="old('slug')" required />
                                <button type="button" id="generate-slug" class="ml-2 px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">
                                    Generate
                                </button>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">The URL-friendly version of the title (auto-generated if left empty)</p>
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>

                        <!-- Content Field with TinyMCE -->
                        <div>
                            <x-input-label  for="content" :value="__('Content')" class="mb-1 dark:text-gray-200"/>
                            <textarea id="content" name="content" class="tinymce-editor mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

          
                        <!-- Category Field -->
                        <div>
                            <x-input-label for="category_id" :value="__('Category')" class="dark:text-gray-200" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                                <option value="">Select Category</option>
                                {{-- Example static options, remove if using dynamic categories --}}
                             
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <!-- Image Field -->
                        <div>
                            <x-input-label for="image" :value="__('Featured Image')" class="dark:text-gray-200" />
                            <input id="image" type="file" name="image" class="block mt-1 w-full border border-gray-300 dark:border-gray-600 p-2 rounded-md" />
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload a featured image for your post (optional)</p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Tags Field -->
                        <div>
                            <x-input-label class="dark:text-gray-200" for="tags" :value="__('Tags')" />
                            <input id="tags" type="text" name="tags" value="{{ old('tags') }}" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600" />
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Enter tags separated by commas</p>
                            <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                        </div>

                        <!-- Suggested Categories -->
                        <div id="suggested-categories" class="mt-2"></div>
                  
                        <!-- Submit Button -->
                        <div class="flex items-center gap-4">
                            <x-primary-button class="dark:bg-purple-500">{{ __('Create Post') }}</x-primary-button>
                            <a href="{{ route('editor.posts.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Slug generation script -->
    <x-slot name="scripts">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Generate slug from title
                const titleInput = document.getElementById('title');
                const slugInput = document.getElementById('slug');
                const generateSlugBtn = document.getElementById('generate-slug');

                function createSlug(text) {
                    return text.toString().toLowerCase()
                        .replace(/\s+/g, '-')           // Replace spaces with -
                        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                        .replace(/^-+/, '')             // Trim - from start of text
                        .replace(/-+$/, '');            // Trim - from end of text
                }

                // Auto-generate slug on title change
                titleInput.addEventListener('blur', function() {
                    if (slugInput.value === '') {
                        slugInput.value = createSlug(titleInput.value);
                    }
                });

                // Generate slug on button click
                generateSlugBtn.addEventListener('click', function() {
                    slugInput.value = createSlug(titleInput.value);
                });

                // Suggested Categories Script
                const tagsInput = document.getElementById('tags');
                const categorySelect = document.getElementById('category_id');
                const suggestedDiv = document.getElementById('suggested-categories');
                // Get categories from the select options
                const categories = Array.from(categorySelect.options)
                    .filter(opt => opt.value)
                    .map(opt => ({id: opt.value, name: opt.textContent.trim()}));

                function suggestCategories() {
                    const tags = tagsInput.value.toLowerCase().split(',').map(t => t.trim()).filter(Boolean);
                    let matches = [];
                    if (tags.length) {
                        matches = categories.filter(cat =>
                            tags.some(tag => cat.name.toLowerCase().includes(tag))
                        );
                    }
                    if (!matches.length) {
                        const title = titleInput.value.toLowerCase();
                        matches = categories.filter(cat =>
                            cat.name.toLowerCase().split(' ').some(word => title.includes(word))
                        );
                    }
                    if (matches.length) {
                        suggestedDiv.innerHTML = '<div class="text-sm text-gray-600 dark:text-gray-300 mb-1">Suggested Categories:</div>' +
                            matches.map(cat =>
                                `<button type="button" class="suggested-cat px-2 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 rounded mr-2 mb-1 text-xs" data-id="${cat.id}">${cat.name}</button>`
                            ).join('');
                    } else {
                        suggestedDiv.innerHTML = '';
                    }
                }

                tagsInput.addEventListener('input', suggestCategories);
                titleInput.addEventListener('input', suggestCategories);
                suggestedDiv.addEventListener('click', function(e) {
                    if (e.target.classList.contains('suggested-cat')) {
                        categorySelect.value = e.target.getAttribute('data-id');
                    }
                });
            });
        </script>
    </x-slot>
    
    <!-- Override the footer slot to remove it -->
    <x-slot name="footer"></x-slot>
</x-app-layout>
