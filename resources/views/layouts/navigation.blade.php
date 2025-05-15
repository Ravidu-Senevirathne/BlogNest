<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check() ? (auth()->user()->hasRole('editor') ? route('editor.dashboard') : (auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('reader.dashboard'))) : route('dashboard') }}">
                        <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">BlogNest</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <!-- Change Dashboard link to direct role-specific route -->
                    @auth
                        @if(auth()->user()->hasRole('admin'))
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                        @elseif(auth()->user()->hasRole('editor'))
                            <x-nav-link :href="route('editor.dashboard')" :active="request()->routeIs('editor.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('reader.dashboard')" :active="request()->routeIs('reader.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                        @endif
                    @endauth
                    
                    <!-- Blog Link (available to all users) -->
                    <x-nav-link :href="route('blog.index')" :active="request()->routeIs('blog.index')">
                        {{ __('Browse Posts') }}
                    </x-nav-link>
                    
                    <!-- Reader Links -->
                    @role('reader|admin')
                        <!-- Removed duplicate "Browse Posts" link since Dashboard already points to reader.dashboard -->
                        <x-nav-link :href="route('reader.bookmarks')" :active="request()->routeIs('reader.bookmarks')">
                            {{ __('My Bookmarks') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reader.search')" :active="request()->routeIs('reader.search')">
                            {{ __('Search') }}
                        </x-nav-link>
                    @endrole
                    
                    <!-- Editor-specific Links -->
                    @role('editor')
                        <x-nav-link :href="route('editor.posts.index')" :active="request()->routeIs('editor.posts.*')">
                            {{ __('My Posts') }}
                        </x-nav-link>
                        <x-nav-link :href="route('editor.posts.create')" :active="request()->routeIs('editor.posts.create')">
                            {{ __('Create Post') }}
                        </x-nav-link>
                    @endrole
                    
                    <!-- Admin-specific Links -->
                    @role('admin')
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.posts.index')" :active="request()->routeIs('admin.posts.*')">
                            {{ __('Posts') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                            {{ __('Categories') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.tags.index')" :active="request()->routeIs('admin.tags.*')">
                            {{ __('Tags') }}
                        </x-nav-link>
                    @endrole
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Theme Toggle -->
                <div class="mr-3">
                    <x-theme.toggle />
                </div>
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Update mobile Dashboard link too -->
            @auth
                @if(auth()->user()->hasRole('admin'))
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                @elseif(auth()->user()->hasRole('editor'))
                    <x-responsive-nav-link :href="route('editor.dashboard')" :active="request()->routeIs('editor.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('reader.dashboard')" :active="request()->routeIs('reader.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
            
            <!-- Blog Link (Mobile - available to all users) -->
            <x-responsive-nav-link :href="route('blog.index')" :active="request()->routeIs('blog.index')">
                {{ __('Browse Posts') }}
            </x-responsive-nav-link>
            
            <!-- Reader Links -->
            @role('reader|admin')
                <!-- Removed duplicate "Browse Posts" link in mobile menu as well -->
                <x-responsive-nav-link :href="route('reader.bookmarks')" :active="request()->routeIs('reader.bookmarks')">
                    {{ __('My Bookmarks') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reader.search')" :active="request()->routeIs('reader.search')">
                    {{ __('Search') }}
                </x-responsive-nav-link>
            @endrole
            
            <!-- Editor-specific Links (Mobile) -->
            @role('editor')
                <x-responsive-nav-link :href="route('editor.posts.index')" :active="request()->routeIs('editor.posts.*')">
                    {{ __('My Posts') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('editor.posts.create')" :active="request()->routeIs('editor.posts.create')">
                    {{ __('Create Post') }}
                </x-responsive-nav-link>
            @endrole
            
            <!-- Admin-specific Links (Mobile) -->
            @role('admin')
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Users') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.posts.index')" :active="request()->routeIs('admin.posts.*')">
                    {{ __('Posts') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                    {{ __('Categories') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.tags.index')" :active="request()->routeIs('admin.tags.*')">
                    {{ __('Tags') }}
                </x-responsive-nav-link>
            @endrole
        </div>
        
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
                
                <!-- Theme Toggle (Mobile) -->
                <div class="px-4 py-2">
                    <x-theme.toggle />
                </div>
            </div>
        </div>
    </div>
</nav>
