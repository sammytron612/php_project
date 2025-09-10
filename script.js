document.addEventListener('mousemove', function(event) {
    const sidebar = document.getElementById('sidebar');
    if (event.clientX === 0) {
        sidebar.classList.remove('sidebar_hidden');
    } else if (event.clientX > 200) {
        sidebar.classList.add('sidebar_hidden');
    }
});