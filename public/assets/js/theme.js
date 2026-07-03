$(document).ready(function () {
    const html = $('html');
    const savedTheme = localStorage.getItem('theme') || 'light';
    html.attr('data-theme', savedTheme);
    updateToggleIcon(savedTheme);

    $('#theme-toggle').on('click', function () {
        const current = html.attr('data-theme');
        const newTheme = current === 'light' ? 'dark' : 'light';
        html.attr('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateToggleIcon(newTheme);
    });

    function updateToggleIcon(theme) {
        $('#theme-toggle').html(theme === 'light' ? '🌙' : '☀️');
    }
});