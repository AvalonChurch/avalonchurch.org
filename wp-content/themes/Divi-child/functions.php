<?php
function my_theme_enqueue_styles() { 
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

add_action( 'et_after_main_content', function() {
  echo do_shortcode('[smbtoolbar]');
});

function add_shortcodes_to_excerpt( $excerpt ) {
	return "empty";
	if ( is_admin() ) {
		return $excerpt;
	}

	$excerpt = do_shortcode("[usp_video]usp-file-2[/usp_video]") . ":" . $excerpt;
	return $excerpt;
}

//add_filter( 'get_the_excerpt', 'add_shortcodes_to_excerpt', 999 );
