document.addEventListener('DOMContentLoaded', function() {

    let page = 1;
    let isLoading = false;

    ///// Sidebar show/hide on mouse far left ////
    document.addEventListener('mousemove', function(event) {
    const sidebar = document.getElementById('sidebar');
        if (event.clientX < 10) {
            sidebar.classList.remove('sidebar_hidden');
        } else if (event.clientX > 200) {
            sidebar.classList.add('sidebar_hidden');
        };
    });
    
    /////// Scroll listener to detect when user reaches bottom ////
    window.addEventListener('scroll', function() {

        if (isLoading) return;

        const scrollTop = window.pageYOffset;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;

        //console.log(`ScrollTop: ${scrollTop}, WindowHeight: ${windowHeight}, DocumentHeight: ${documentHeight}`);   
        
        // Check if user has scrolled to bottom ///
        if (scrollTop + windowHeight >= documentHeight) {
            
            page++;
            console.log('Page:', page);
            if (page <= 10) {
                loadMoreContent();
            } else
            {
                window.removeEventListener('scroll', this);
                document.getElementById('footer').style.display = "block";
            }
        }
    });
    
async function loadMoreContent() {
    
    /*    console.log(`Loading more content for page ${page}`);

        const div = document.getElementById('grid-container');
        for (let i = 0; i < 2; i++) { // Add 2 new items


        
        const newContent = `
            <div class="lorem">
                <img src="https://picsum.photos/id/${page * 5 + i}/500/" alt="Placeholder Image" style="float: left; margin-right: 15px; margin-bottom: 10px; max-width: 150px;">
                New Lorem Ipsum content for page ${page}, item ${i + 1}. 
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                Why do we use it?
            </div>
        `;
        console.log(page);
        // Append the new content
        div.insertAdjacentHTML('beforeend', newContent);
    } */
        
        isLoading = true;
        
        console.log(`Loading more content for page ${page}`);

        try {
            // Show loading indicator
            showLoadingSpinner();

            // Make AJAX call
            const response = await fetch('get-content.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    page: page,
                })
            });

            const data = await response.json();
            //console.log('Response from server:', data);
            if (data.success && data.html) {

                // Append the HTML content from database
                const div = document.getElementById('grid-container');
                div.insertAdjacentHTML('beforeend', data.html);
                console.log(`Added content for page ${page}`);

            } else {
                console.log('No more content available');
            }

        } catch (error) {
            console.error('Error loading content:', error);
            // Optionally show error message to user
            
        } finally {
            isLoading = false;
            hideLoadingSpinner();
        }
    }

function showLoadingSpinner() {
        const spinner = document.getElementById('loading-spinner');
        if (spinner) {
            spinner.style.display = 'block';
        }
    }

function hideLoadingSpinner() {
        const spinner = document.getElementById('loading-spinner');
        if (spinner) {
            spinner.style.display = 'none';
        }
    }
});


