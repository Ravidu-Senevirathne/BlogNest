<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BlogNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Initialize dark mode from localStorage
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
        }
    </script>
    <style>
        html.dark body {
            background-color: #1a202c;
            color: #e2e8f0;
        }
        
        html.dark .navbar-dark {
            background-color: #2d3748 !important;
        }
        
        html.dark .card, html.dark .bg-light {
            background-color: #2d3748 !important;
            color: #e2e8f0;
        }
        
        html.dark .text-dark {
            color: #e2e8f0 !important;
        }
        
        .theme-toggle {
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.25rem;
        }
        
        html.light .dark-icon {
            display: block;
        }
        
        html.light .light-icon {
            display: none;
        }
        
        html.dark .dark-icon {
            display: none;
        }
        
        html.dark .light-icon {
            display: block;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">BlogNest Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.posts.index') }}">Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.categories.index') }}">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.tags.index') }}">Tags</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <button id="theme-toggle" class="btn btn-outline-light me-2 theme-toggle">
                        <svg class="dark-icon" width="20" height="20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg class="light-icon" width="20" height="20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col">
                <h1>Welcome to Admin Dashboard</h1>
                <p>Hello, {{ Auth::user()->name }}!</p>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <h1 class="display-4">{{ $totalUsers }}</h1>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">View All</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Posts</h5>
                        <h1 class="display-4">{{ $totalPosts }}</h1>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-primary btn-sm">View All</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Comments</h5>
                        <h1 class="display-4">{{ $totalComments }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pending Posts</h5>
                        <h1 class="display-4">{{ $pendingPosts }}</h1>
                        <a href="{{ route('admin.posts.index', ['status' => 'pending']) }}" class="btn btn-warning btn-sm">Review</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-2">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Posts</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($recentPosts as $post)
                                <a href="{{ route('admin.posts.edit', $post) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $post->title }}</h6>
                                        <small>{{ $post->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">By: {{ $post->user->name }}</p>
                                    <small class="text-{{ $post->status == 'approved' ? 'success' : ($post->status == 'pending' ? 'warning' : 'danger') }}">
                                        Status: {{ ucfirst($post->status) }}
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-primary btn-sm">View All Posts</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Users</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($recentUsers as $user)
                                <a href="{{ route('admin.users.edit', $user) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $user->name }}</h6>
                                        <small>{{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $user->email }}</p>
                                    <small>
                                        Roles: {{ $user->roles->pluck('name')->implode(', ') }}
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">View All Users</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('theme-toggle').addEventListener('click', function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.remove('light');
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        });
    </script>
</body>
</html>
