document.addEventListener('mousemove', function(event) {
    const sidebar = document.getElementById('sidebar');
    if (event.clientX === 0) {
        sidebar.classList.remove('sidebar_hidden');
    } else if (event.clientX > 200) {
        sidebar.classList.add('sidebar_hidden');
    };
});

document.addEventListener('DOMContentLoaded', function() {

    ///// Sidebar show/hide on mouse far left ////
    document.addEventListener('mousemove', function(event) {
    const sidebar = document.getElementById('sidebar');
    if (event.clientX === 0) {
        sidebar.classList.remove('sidebar_hidden');
    } else if (event.clientX > 200) {
        sidebar.classList.add('sidebar_hidden');
    };
    });
    /////// Scroll listener to detect when user reaches bottom ////
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;

        console.log(`ScrollTop: ${scrollTop}, WindowHeight: ${windowHeight}, DocumentHeight: ${documentHeight}`);   
        
        // Check if user has scrolled to bottom within 10px ///
        if (scrollTop + windowHeight >= documentHeight - 10) {
            console.log('User has reached the bottom of the page');
            
            loadMoreContent();
        }
    });
    
    function loadMoreContent() {
        
        console.log('Loading more');
    }
});