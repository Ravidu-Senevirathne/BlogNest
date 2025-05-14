<x-guest-layout>
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 max-w-md w-full mx-auto">
        <!-- Header section -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">BlogNest</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2">One more step to complete your registration</p>
            </div>
            <div>
                <x-theme.toggle />
            </div>
        </div>
        
        <!-- Title section -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Select Your Role</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Choose how you want to use BlogNest</p>
        </div>

        <form method="POST" action="{{ route('social.role.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Editor Role Option -->
                <div>
                    <input type="radio" id="editor" name="role" value="editor" class="hidden peer" required>
                    <label for="editor" class="flex flex-col p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-indigo-600 dark:peer-checked:border-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center text-indigo-600 dark:text-indigo-300 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <span class="font-medium text-gray-900 dark:text-gray-100">Editor</span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Create and publish content</span>
                    </label>
                </div>

                <!-- Reader Role Option -->
                <div>
                    <input type="radio" id="reader" name="role" value="reader" class="hidden peer" checked>
                    <label for="reader" class="flex flex-col p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-indigo-600 dark:peer-checked:border-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center text-green-600 dark:text-green-300 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="font-medium text-gray-900 dark:text-gray-100">Reader</span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Discover and enjoy content</span>
                    </label>
                </div>
            </div>

            <x-input-error :messages="$errors->get('role')" class="mt-2" />

            <div>
                <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 dark:from-indigo-500 dark:to-purple-500 text-white font-medium py-3 px-4 rounded-md transition-colors shadow-md">
                    {{ __('Complete Registration') }}
                </button>
            </div>
        </form>
    </div>

    <x-theme.script />
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get DOM elements
            const roles = {
                editor: {
                    radio: document.getElementById('editor'),
                    label: document.querySelector('label[for="editor"]')
                },
                reader: {
                    radio: document.getElementById('reader'),
                    label: document.querySelector('label[for="reader"]')
                }
            };
            
            // CSS classes for highlighting selected role
            const highlightClasses = ['bg-indigo-50', 'dark:bg-gray-700'];
            
            // Set initial highlight based on default selection
            const initialRole = roles.editor.radio.checked ? 'editor' : 'reader';
            roles[initialRole].label.classList.add(...highlightClasses);
            
            // Handle role selection
            function selectRole(role) {
                // Update visual state
                roles[role].label.classList.add(...highlightClasses);
                roles[role === 'editor' ? 'reader' : 'editor'].label.classList.remove(...highlightClasses);
                
                // Update form state
                roles[role].radio.checked = true;
            }
            
            // Add click event listeners
            roles.editor.label.addEventListener('click', () => selectRole('editor'));
            roles.reader.label.addEventListener('click', () => selectRole('reader'));
        });
    </script>
</x-guest-layout>
