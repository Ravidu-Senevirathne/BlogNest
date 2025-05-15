<nav class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                        {{ config('app.name', 'BlogNest') }}
                    </a>
                </div>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-8">
                <a href="#home" class="scroll-link px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">Home</a>
                <a href="#features" class="scroll-link px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">Features</a>
                <a href="#blog" class="scroll-link px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">Blog</a>
                <a href="#join" class="scroll-link px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">Join Us</a>
                <div class="ml-3">
                    <x-theme.toggle />
                </div>
                @auth
                    <a href="{{ auth()->user()->hasRole('editor') ? route('editor.dashboard') : (auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('reader.dashboard')) }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 text-sm font-medium">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Register
                    </a>
                @endauth
            </div>
            <div class="flex items-center sm:hidden">
                <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="sm:hidden mobile-menu hidden">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="#home" class="scroll-link block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">Home</a>
            <a href="#features" class="scroll-link block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">Features</a>
            <a href="#blog" class="scroll-link block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">Blog</a>
            <a href="#join" class="scroll-link block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">Join Us</a>
            @auth
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">Log in</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">Register</a>
            @endauth
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.querySelector('.mobile-menu-button');
        const menu = document.querySelector('.mobile-menu');
        
        button.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('.scroll-link').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Hide mobile menu if it's open
                menu.classList.add('hidden');
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
