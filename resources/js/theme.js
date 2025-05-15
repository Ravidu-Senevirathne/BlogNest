// Theme initialization and toggling functionality
const initializeTheme = () => {
    // On page load or when changing themes, check if the user has a preference
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        document.documentElement.classList.remove('light');
        updateThemeIcons(true);
    } else {
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
        updateThemeIcons(false);
    }
};

const toggleTheme = () => {
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
        localStorage.theme = 'light';
        updateThemeIcons(false);
    } else {
        document.documentElement.classList.remove('light');
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
        updateThemeIcons(true);
    }
};

const updateThemeIcons = (isDark) => {
    const darkIcons = document.querySelectorAll('#theme-toggle-dark-icon');
    const lightIcons = document.querySelectorAll('#theme-toggle-light-icon');

    darkIcons.forEach(icon => {
        icon.classList.toggle('hidden', isDark);
    });

    lightIcons.forEach(icon => {
        icon.classList.toggle('hidden', !isDark);
    });
};

// Initialize theme when the script loads
initializeTheme();

// Export functions to make them available globally
window.toggleTheme = toggleTheme;
window.initializeTheme = initializeTheme;
