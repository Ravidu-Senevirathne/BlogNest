<script>
    // On page load or when changing themes
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    // Add event listener to toggle button when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize theme toggle icons based on current theme
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        
        if (themeToggleDarkIcon && themeToggleLightIcon) {
            if (localStorage.getItem('color-theme') === 'dark' || 
                (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }
        }
        
        // Add click event listener to theme toggle button
        const themeToggleBtn = document.getElementById('theme-toggle');
        
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                // Toggle icons
                const darkIcon = document.getElementById('theme-toggle-dark-icon');
                const lightIcon = document.getElementById('theme-toggle-light-icon');
                
                if (darkIcon && lightIcon) {
                    darkIcon.classList.toggle('hidden');
                    lightIcon.classList.toggle('hidden');
                }
                
                // Toggle theme
                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }
            });
        }
    });
</script>
