<?php 

add_action('wp_enqueue_scripts', 'theme_style');
add_action('wp_enqueue_scripts', 'theme_scripts');

//connect style
function theme_style()
{
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array('style'));
    wp_enqueue_style('templatemo', get_template_directory_uri() . '/assets/css/templatemo.css', array('bootstrap'));
    wp_enqueue_style('custom', get_template_directory_uri() . '/assets/css/custom.css', array('templatemo'));
    wp_enqueue_style('fonts', 'https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap', array('custom'));
    wp_enqueue_style('fontawesome', get_template_directory_uri() . '/assets/css/fontawesome.min.css');
}

// connect script
function theme_scripts()
{
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', get_template_directory_uri() . '/assets/js/jquery-1.11.0.min.js');
    wp_enqueue_script('jquery-migrate', get_template_directory_uri() . '/assets/js/jquery-migrate-1.2.1.min.js');
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('templatemo', get_template_directory_uri() . '/assets/js/templatemo.js', array('jquery', '1.0.0', true));
    wp_enqueue_script('custom', get_template_directory_uri() . '/assets/js/custom.js', array('jquery', '1.0.0', true));
}