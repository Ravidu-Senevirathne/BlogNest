<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Post') }}: {{ $post->title }}
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
                    <form action="{{ route('editor.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Title Field -->
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $post->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Slug Field -->
                        <div>
                            <x-input-label for="slug" :value="__('Slug')" />
                             <div class="flex items-center">
                                <x-text-input id="slug" class="block mt-1 w-full" type="text" name="slug" :value="old('slug', $post->slug)" required />
                                <button type="button" id="generate-slug" class="ml-2 px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">
                                    Generate
                                </button>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">The URL-friendly version of the title (auto-generated if left empty or on button click)</p>
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>

                        <!-- Content Field with TinyMCE -->
                        <div>
                            <x-input-label for="content" :value="__('Content')" class="mb-1" />
                            <textarea id="content" name="content" class="tinymce-editor mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('content', $post->content) }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <!-- Category Field -->
                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                                <option value="">Select Category</option>
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>
                        
                    
                      

                        <!-- Image Field -->
                        <div>
                            <x-input-label for="image" :value="__('Featured Image')" />
                            <input id="image" type="file" name="image" class="block mt-1 w-full border border-gray-300 dark:border-gray-600 p-2 rounded-md" />
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload a new featured image to replace the current one (optional)</p>
                            @if($post->image)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Current Image:</p>
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="mt-1 w-48 h-auto rounded shadow">
                                </div>
                            @endif
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Tags Field -->
                        <div>
                            <x-input-label for="tags" :value="__('Tags')" />
                            <input id="tags" type="text" name="tags" value="{{ old('tags', ($post->tags ? $post->tags->pluck('name')->implode(',') : '')) }}" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600" />
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Enter tags separated by commas</p>
                            <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                        </div>
                        
                        <!-- Suggested Categories -->
                        <div id="suggested-categories" class="mt-2"></div>

                        <!-- Submit Button -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Post') }}</x-primary-button>
                            <a href="{{ route('editor.posts.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
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

                generateSlugBtn.addEventListener('click', function() {
                    slugInput.value = createSlug(titleInput.value);
                });
                
                // Suggested Categories Script (same as create.blade.php)
                const tagsInput = document.getElementById('tags');
                const categorySelect = document.getElementById('category_id');
                const suggestedDiv = document.getElementById('suggested-categories');
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
                // Initial suggestion call
                suggestCategories();
            });
        </script>
    </x-slot>
</x-app-layout>
