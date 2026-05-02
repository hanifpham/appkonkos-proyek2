import './bootstrap';

const THEME_KEY = 'theme';
const DASHBOARD_THEME_KEY = 'mitra-dark-mode';

const canUseLocalStorage = () => {
    try {
        const testKey = '__appkonkos_theme_test__';
        window.localStorage.setItem(testKey, testKey);
        window.localStorage.removeItem(testKey);

        return true;
    } catch {
        return false;
    }
};

const storageAvailable = canUseLocalStorage();

const getStoredTheme = () => {
    if (!storageAvailable) {
        return null;
    }

    const publicTheme = window.localStorage.getItem(THEME_KEY);

    if (publicTheme === 'dark' || publicTheme === 'light') {
        return publicTheme;
    }

    const dashboardTheme = window.localStorage.getItem(DASHBOARD_THEME_KEY);

    if (dashboardTheme === 'true' || dashboardTheme === 'false') {
        return dashboardTheme === 'true' ? 'dark' : 'light';
    }

    return null;
};

const getPreferredTheme = () => {
    const storedTheme = getStoredTheme();

    if (storedTheme !== null) {
        return storedTheme;
    }

    return window.matchMedia?.('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

const persistTheme = (theme) => {
    if (!storageAvailable) {
        return;
    }

    window.localStorage.setItem(THEME_KEY, theme);
    window.localStorage.setItem(DASHBOARD_THEME_KEY, theme === 'dark' ? 'true' : 'false');
};

const applyTheme = (theme) => {
    const isDark = theme === 'dark';

    document.documentElement.classList.toggle('dark', isDark);
    document.documentElement.style.colorScheme = theme;

    return isDark;
};

window.appkonkosTheme = {
    init() {
        const theme = getPreferredTheme();
        persistTheme(theme);

        const isDark = applyTheme(theme);

        window.dispatchEvent(new CustomEvent('appkonkos-theme-changed', {
            detail: { darkMode: isDark, theme },
        }));

        return isDark;
    },

    isDark() {
        return document.documentElement.classList.contains('dark');
    },

    set(darkMode) {
        const theme = darkMode ? 'dark' : 'light';
        persistTheme(theme);
        const isDark = applyTheme(theme);

        window.dispatchEvent(new CustomEvent('appkonkos-theme-changed', {
            detail: { darkMode: isDark, theme },
        }));

        return isDark;
    },

    toggle() {
        return this.set(!this.isDark());
    },
};

window.appkonkosTheme.init();
