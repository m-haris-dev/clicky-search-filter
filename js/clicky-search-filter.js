jQuery(document).ready(function($) {
    function load_case_studies(page = 1) {
        // Show the loading image
        $('#loading-image').show();

        $.ajax({
            url: clickySearchFilter.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: $('#clicky-search-filter-form').serialize() + '&action=clicky_search_filter&page=' + page,
            success: function(response) {
                $('#loading-image').hide();  // Hide the loading image

                // Clear results if it's the first page
                if (page === 1) {
                    $('#case-studies-results').html('');
                }

                // Check if there are posts
                if (response.posts.length === 0) {
                    // Show a Page Not Found message
                    $('#case-studies-results').html(' <div class="clicky-error"><p>No results found.</p></div>');
                    $('#load-more').hide();  // Hide Load More button
                } else {
                    // Loop through posts and append them to the results div
                    $.each(response.posts, function(index, post) {
                        var defaultImage = post.weburl + '/wp-content/plugins/clicky-search-filter/default.jpg';
                        var thumbnail = post.thumbnail || defaultImage;  // Replace with the actual path to your default image
                        var readmoreImg = post.weburl + '/wp-content/plugins/clicky-search-filter/arrow-right.png';
                        var excerpt = post.excerpt;
                        if (excerpt.length > 100) {
                            excerpt = excerpt.substring(0, 100) + '...';
                        }
                        var title = post.title;
                        if (title.length > 50) {
                            title = title.substring(0, 50) + '...';
                        }
                        var postHtml = `
                        <div class="clicky-box" onclick="location.href='${post.permalink}';">
                            <div class="clicky-inner">
                                <img src="${thumbnail}" alt="">
                                <div class="clicky-content">
                                    <h4>${title}</h4>
                                    <div class="clicky-excerpt">
                                        ${excerpt}
                                    </div>
                                    <a href="${post.permalink}">Read More 
                                        <img class="clicky-arrow" src="${readmoreImg}" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>`;
                        $('#case-studies-results').append(postHtml);
                    });

                    // Show/hide Load More button based on `has_more_posts`
                    if (response.has_more_posts) {
                        $('#load-more').data('page', page).show();
                    } else {
                        $('#load-more').hide();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', status, error);
                $('#loading-image').hide();  // Hide the loading image on error
            }
        });
    }

    function load_blogs(page = 1) {
        // Show the loading image
        $('#loading-image').show();

        $.ajax({
            url: clickySearchFilter.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: $('#clicky-blog-filter-form').serialize() + '&action=clicky_blog_search_filter&page=' + page,
            success: function(response) {
                $('#loading-image').hide();  // Hide the loading image

                // Clear results if it's the first page
                if (page === 1) {
                    $('#blogs-results').html('');
                }

                // Check if there are posts
                if (response.posts.length === 0) {
                    // Show a Page Not Found message
                    $('#blogs-results').html(' <div class="clicky-error"><p>No results found.</p></div>');
                    $('#load-blog-more').hide();  // Hide Load More button
                } else {
                    // Loop through posts and append them to the results div
                    $.each(response.posts, function(index, post) {
                        var defaultImage = post.weburl + '/wp-content/plugins/clicky-search-filter/default.jpg';
                        var thumbnail = post.thumbnail || defaultImage;  // Replace with the actual path to your default image
                        var readmoreImg = post.weburl + '/wp-content/plugins/clicky-search-filter/arrow-right.png';
                        var excerpt = post.excerpt;
                        if (excerpt.length > 100) {
                            excerpt = excerpt.substring(0, 100) + '...';
                        }
                        var title = post.title;
                        if (title.length > 50) {
                            title = title.substring(0, 50) + '...';
                        }
                        var postHtml = `
                        <div class="clicky-box" onclick="location.href='${post.permalink}';">
                            <div class="clicky-inner">
                                <img src="${thumbnail}" alt="">
                                <div class="clicky-content">
                                    <h4>${title}</h4>
                                    <div class="clicky-excerpt">
                                        ${excerpt}
                                    </div>
                                    <a href="${post.permalink}">Read More 
                                        <img class="clicky-arrow" src="${readmoreImg}" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>`;
                        $('#blogs-results').append(postHtml);
                    });

                    // Show/hide Load More button based on `has_more_posts`
                    if (response.has_more_posts) {
                        $('#load-blog-more').data('page', page).show();
                    } else {
                        $('#load-blog-more').hide();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', status, error);
                $('#loading-image').hide();  // Hide the loading image on error
            }
        });
    }


    // Event handlers as before
    $('#filter-button').click(function() {
        load_case_studies(1);
        if ($('.category-header').hasClass("active")) {
            $('.category-header').removeClass("active");
            $('.category-header').next(".category-content").slideToggle(300);
        }
    });

    $('#filter-blog-button').click(function() {
        load_blogs(1);
        if ($('.category-header').hasClass("active")) {
            $('.category-header').removeClass("active");
            $('.category-header').next(".category-content").slideToggle(300);
        }
    });

    $(document).on('click', '#load-more', function() {
        let nextPage = parseInt($(this).data('page')) + 1;
        load_case_studies(nextPage);
    });

    $(document).on('click', '#load-blog-more', function() {
        let nextPage = parseInt($(this).data('page')) + 1;
        load_blogs(nextPage);
    });

    // Load default posts on page load
    load_case_studies();

    load_blogs();


    $(".category-header").click(function(){
        $(this).toggleClass("active");
        $(this).next(".category-content").slideToggle(300);
    });

});
