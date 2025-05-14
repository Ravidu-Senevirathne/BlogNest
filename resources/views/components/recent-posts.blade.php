<x-theme.section bg="bg-gray-50 dark:bg-gray-900" id="blog">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white sm:text-4xl">Recent Publications</h2>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">Discover trending stories from our writers</p>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Sample Post 1 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
            <img src="https://via.placeholder.com/600x400" alt="Post thumbnail" class="w-full h-48 object-cover">
            <div class="p-6">
                <div class="flex items-center mb-2">
                    <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-300 text-xs px-2 py-1 rounded-full">Technology</span>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">The Future of Web Development</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">Exploring the latest trends and technologies shaping the web development landscape in 2023 and beyond.</p>
                <div class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                    <div class="ml-2">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Sarah Johnson</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">June 12, 2023</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sample Post 2 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
            <img src="https://via.placeholder.com/600x400" alt="Post thumbnail" class="w-full h-48 object-cover">
            <div class="p-6">
                <div class="flex items-center mb-2">
                    <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 text-xs px-2 py-1 rounded-full">Lifestyle</span>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">Mindfulness in the Digital Age</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">How to maintain focus and mental wellbeing in an increasingly connected and distracted world.</p>
                <div class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                    <div class="ml-2">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Michael Chen</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">June 10, 2023</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sample Post 3 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
            <img src="https://via.placeholder.com/600x400" alt="Post thumbnail" class="w-full h-48 object-cover">
            <div class="p-6">
                <div class="flex items-center mb-2">
                    <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 text-xs px-2 py-1 rounded-full">Food</span>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">Culinary Adventures: Street Food Edition</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">Discover the best street food dishes from around the world that you need to try at least once.</p>
                <div class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                    <div class="ml-2">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Elena Rodriguez</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">June 8, 2023</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-10">
        <a href="#" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 font-medium hover:text-indigo-800 dark:hover:text-indigo-300">
            View all posts
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </a>
    </div>
</x-theme.section>
