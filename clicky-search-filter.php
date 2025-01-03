<?php
/*
Plugin Name: Clicky Search Filter
Description: A custom plugin for filtering case studies with AJAX.
Version: 1.0
Author: ClickySoft
*/

function enqueue_clicky_search_filter_scripts() {
    wp_enqueue_script('clicky-search-filter-ajax', plugins_url('/js/clicky-search-filter.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('clicky-search-filter-ajax', 'clickySearchFilter', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
    wp_enqueue_style('clicky-search-filter-style', plugins_url('/css/clicky-search-filter.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'enqueue_clicky_search_filter_scripts');

// Shortcode to display the search filter form and results

function clicky_search_filter_shortcode() {
    ob_start(); ?>

    <form id="clicky-search-filter-form">

    <div class="category-dropdown">
        <div class="category-header">Categories
            <svg class="fa-svg-chevron-down e-font-icon-svg e-fas-chevron-down" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z"></path></svg>
        </div>
        <div class="category-content">
        <h4>Museum Size</h4>
        <?php
        $museum_sizes = get_terms(array('taxonomy' => 'museum-size', 'hide_empty' => false));
        foreach ($museum_sizes as $size) {
            echo '<label class="clicky-check"><input type="checkbox" name="museum-size[]" value="' . $size->slug . '">' . $size->name . '<span class="checkmark"></span></label>';
        }
        ?>

        <h4>Museum Type</h4>
        <?php
        $museum_types = get_terms(array('taxonomy' => 'museum-type', 'hide_empty' => false));
        foreach ($museum_types as $type) {
            echo '<label class="clicky-check"><input type="checkbox" name="museum-type[]" value="' . $type->slug . '">' . $type->name . '<span class="checkmark"></span></label>';
        }
        ?>

        <h4>Square Footage</h4>
        <?php
        $square_footages = get_terms(array('taxonomy' => 'square-footage', 'hide_empty' => false));
        foreach ($square_footages as $footage) {
            echo '<label class="clicky-check"><input type="checkbox" name="square-footage[]" value="' . $footage->slug . '">' . $footage->name . '<span class="checkmark"></span></label>';
        }
        ?>

        <h4>Visitor Number</h4>
        <?php
        $visitor_numbers = get_terms(array('taxonomy' => 'visitor-number', 'hide_empty' => false));
        foreach ($visitor_numbers as $visitor) {
            echo '<label class="clicky-check"><input type="checkbox" name="visitor-number[]" value="' . $visitor->slug . '">' . $visitor->name . '<span class="checkmark"></span></label>';
        }
        ?>
        </div>
    </div>
    <div class="searchbar">
        <svg aria-hidden="true" class="e-font-icon-svg e-fas-search" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg>
        <input type="text" name="search" id="search" placeholder="Search...">
        <button type="button" id="filter-button">Search</button>
    </div>
        
    </form>

    <!-- Loading image -->
    <div class="loading-area">
        <img id="loading-image" src="<?php echo plugins_url('/loader.gif', __FILE__); ?>" alt="Loading..." style="display:none; margin: 0 auto;"/>
    </div>    
    <div id="case-studies-results">
        <!-- AJAX results will be displayed here -->
    </div>
    
    <div id="case-studies-msg"></div>

    <!-- Placeholder for Load More button -->
    <div id="load-more-container">
        <button type="button" id="load-more" style="display:none;">Load More</button>
    </div>

    <?php return ob_get_clean();
}
add_shortcode('clicky_search_filter', 'clicky_search_filter_shortcode');

// Blog Post Search Filter

function clicky_blog_search_filter_shortcode() {
    ob_start(); ?>

    <form id="clicky-blog-filter-form">

    <div class="category-dropdown">
        <div class="category-header">Categories
            <svg class="fa-svg-chevron-down e-font-icon-svg e-fas-chevron-down" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z"></path></svg>
        </div>
        <div class="category-content">
        <h4>Museum Size</h4>
        <?php
        $museum_sizes = get_terms(array('taxonomy' => 'blog-museum-size', 'hide_empty' => false));
        foreach ($museum_sizes as $size) {
            echo '<label class="clicky-check"><input type="checkbox" name="blog-museum-size[]" value="' . $size->slug . '">' . $size->name . '<span class="checkmark"></span></label>';
        }
        ?>

        <h4>Museum Type</h4>
        <?php
        $museum_types = get_terms(array('taxonomy' => 'blog-museum-type', 'hide_empty' => false));
        foreach ($museum_types as $type) {
            echo '<label class="clicky-check"><input type="checkbox" name="blog-museum-type[]" value="' . $type->slug . '">' . $type->name . '<span class="checkmark"></span></label>';
        }
        ?>

        <h4>Square Footage</h4>
        <?php
        $square_footages = get_terms(array('taxonomy' => 'blog-square-footage', 'hide_empty' => false));
        foreach ($square_footages as $footage) {
            echo '<label class="clicky-check"><input type="checkbox" name="blog-square-footage[]" value="' . $footage->slug . '">' . $footage->name . '<span class="checkmark"></span></label>';
        }
        ?>

        <h4>Visitor Number</h4>
        <?php
        $visitor_numbers = get_terms(array('taxonomy' => 'blog-visitor-number', 'hide_empty' => false));
        foreach ($visitor_numbers as $visitor) {
            echo '<label class="clicky-check"><input type="checkbox" name="blog-visitor-number[]" value="' . $visitor->slug . '">' . $visitor->name . '<span class="checkmark"></span></label>';
        }
        ?>
        </div>
    </div>
    <div class="searchbar">
        <svg aria-hidden="true" class="e-font-icon-svg e-fas-search" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg>
        <input type="text" name="search" id="search" placeholder="Search...">
        <button type="button" id="filter-blog-button">Search</button>
    </div>
        
    </form>

    <!-- Loading image -->
    <div class="loading-area">
        <img id="loading-image" src="<?php echo plugins_url('/loader.gif', __FILE__); ?>" alt="Loading..." style="display:none; margin: 0 auto;"/>
    </div>    
    <div id="blogs-results">
        <!-- AJAX results will be displayed here -->
    </div>

    <!-- Placeholder for Load More button -->
    <div id="load-more-container">
        <button type="button" id="load-blog-more" style="display:none;">Load More</button>
    </div>

    <?php return ob_get_clean();
}
add_shortcode('clicky_blog_search_filter', 'clicky_blog_search_filter_shortcode');

// Use Cases Filter

function clicky_search_filter_ajax_handler() {
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = 9;

    $args = array(
        'post_type' => 'case-studies',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        's' => isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '',
        'tax_query' => array('relation' => 'AND'),
        'orderby' => 'date',  
        'order' => 'DESC' 
    );

    $taxonomies = array('museum-size', 'museum-type', 'square-footage', 'visitor-number');
    foreach ($taxonomies as $taxonomy) {
        if (isset($_POST[$taxonomy]) && !empty($_POST[$taxonomy])) {
            $args['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => array_map('sanitize_text_field', $_POST[$taxonomy])
            );
        }
    }

    $query = new WP_Query($args);

    $posts_data = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Collect data for each post
            $posts_data[] = [
                'title' => get_the_title(),
                'permalink' => get_permalink(),
                'excerpt' => get_the_excerpt(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                'weburl' => get_home_url(),
                // Add other fields as necessary
            ];
        }
    }

    wp_reset_postdata();

    // Check if there are more posts available
    $has_more_posts = $query->max_num_pages > intval($_POST['page']);

    // Return JSON data
    wp_send_json([
        'posts' => $posts_data,
        'has_more_posts' => $has_more_posts,
    ]);

    wp_die();

}
add_action('wp_ajax_clicky_search_filter', 'clicky_search_filter_ajax_handler');
add_action('wp_ajax_nopriv_clicky_search_filter', 'clicky_search_filter_ajax_handler');

// Blog Posts Filter

function clicky_blog_search_filter_ajax_handler() {
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = 9;

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        's' => isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '',
        'tax_query' => array('relation' => 'AND'),
        'orderby' => 'date',  
        'order' => 'DESC' 
    );

    $taxonomies = array('blog-museum-size', 'blog-museum-type', 'blog-square-footage', 'blog-visitor-number');
    foreach ($taxonomies as $taxonomy) {
        if (isset($_POST[$taxonomy]) && !empty($_POST[$taxonomy])) {
            $args['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => array_map('sanitize_text_field', $_POST[$taxonomy])
            );
        }
    }

    $query = new WP_Query($args);
    
    $posts_data = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Collect data for each post
            $posts_data[] = [
                'title' => get_the_title(),
                'permalink' => get_permalink(),
                'excerpt' => get_the_excerpt(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                'weburl' => get_home_url(),
                // Add other fields as necessary
            ];
        }
    }

    wp_reset_postdata();

    // Check if there are more posts available
    $has_more_posts = $query->max_num_pages > intval($_POST['page']);

    // Return JSON data
    wp_send_json([
        'posts' => $posts_data,
        'has_more_posts' => $has_more_posts,
    ]);

    wp_die();

}
add_action('wp_ajax_clicky_blog_search_filter', 'clicky_blog_search_filter_ajax_handler');
add_action('wp_ajax_nopriv_clicky_blog_search_filter', 'clicky_blog_search_filter_ajax_handler');

