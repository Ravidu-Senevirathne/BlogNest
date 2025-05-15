<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Welcome to BlogNest') }}
            </h2>
            <span class="text-indigo-600 dark:text-indigo-400">{{ Auth::user()->name }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mb-3">Welcome to Your BlogNest Dashboard</h3>
                        <p class="text-gray-600 dark:text-gray-300">Discover the power of blogging with our intuitive platform.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                        <div class="bg-indigo-50 dark:bg-gray-700 p-5 rounded-lg border-l-4 border-indigo-500">
                            <h4 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 mb-2">Quick Actions</h4>
                            <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                                <li>
                                    <a href="/blog" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                        <span class="inline-block w-5">→</span> Browse Articles
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile.edit') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                        <span class="inline-block w-5">→</span> Edit Profile
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="bg-indigo-50 dark:bg-gray-700 p-5 rounded-lg border-l-4 border-indigo-500">
                            <h4 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 mb-2">Recent Articles</h4>
                            <p class="text-gray-700 dark:text-gray-300">Stay updated with the latest content.</p>
                            <a href="/blog" class="block mt-4 text-indigo-600 dark:text-indigo-400 font-medium hover:underline">
                                View All Articles
                            </a>
                        </div>
                        
                        <div class="bg-indigo-50 dark:bg-gray-700 p-5 rounded-lg border-l-4 border-indigo-500">
                            <h4 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 mb-2">Get Started</h4>
                            <p class="text-gray-700 dark:text-gray-300">Explore all the features BlogNest has to offer.</p>
                            <div class="mt-4">
                                <a href="#" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-md text-sm font-medium inline-block">
                                    Explore Features
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
