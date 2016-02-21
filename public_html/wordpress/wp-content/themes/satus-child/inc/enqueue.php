<?php

add_action( 'wp_enqueue_scripts', 'ge_theme_enqueue_styles' );

function ge_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/assets/css/main.css' );
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/assets/css/main-ie.css' );
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/assets/css/editor.css' );

    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}

function ge_theme_enqueue_child_scripts() {
    wp_enqueue_script( 'child-script', get_stylesheet_directory_uri() . '/assets/js/custom.js' );
}

add_action( 'wp_enqueue_scripts', 'ge_theme_enqueue_child_scripts' );
?>