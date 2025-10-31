<?php
// /inc/setup.php

// Evita acceso directo al archivo
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Configuración base del child theme.
 */
function mm_child_theme_setup() {
  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'responsive-embeds' );
  add_theme_support( 'custom-logo', array(
    'height'      => 120,
    'width'       => 400,
    'flex-height' => true,
    'flex-width'  => true,
  ) );

  register_nav_menus(
    array(
      'primary' => __( 'Menú principal', 'mw25-child' ),
      'footer'  => __( 'Menú del pie', 'mw25-child' ),
    )
  );
}
add_action( 'after_setup_theme', 'mm_child_theme_setup' );

/**
 * Encola estilos y scripts globales del child theme.
 */
function mm_enqueue_assets() {

  // Google Fonts
  wp_enqueue_style(
    'mm-fonts',
    'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=EB+Garamond:wght@400;500;700&display=swap',
    array(),
    null
  );

  // CSS del child theme
  wp_enqueue_style(
    'mm-child',
    get_stylesheet_directory_uri() . '/style.css',
    array( 'mm-fonts' ),
    filemtime( get_stylesheet_directory() . '/style.css' )
  );

  // JS principal del front
  wp_enqueue_script(
    'mm-main',
    get_stylesheet_directory_uri() . '/js/main.js',
    array(),
    filemtime( get_stylesheet_directory() . '/js/main.js' ),
    true
  );

  // Phosphor Icons CDN
  wp_enqueue_script(
    'phosphor-icons',
    'https://unpkg.com/@phosphor-icons/web',
    array(),
    null,
    false
  );
}
add_action( 'wp_enqueue_scripts', 'mm_enqueue_assets' );

/**
 * Preconnect a Google Fonts.
 */
function mm_preconnect_fonts() {
  echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
  echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
}
add_action( 'wp_head', 'mm_preconnect_fonts', 1 );

/**
 * Fallback para el menú principal si no hay uno asignado.
 */
function mm_primary_menu_fallback() {
  echo '<ul id="primary-menu" class="site-nav__list">';
  wp_list_pages( array(
    'title_li' => '',
    'depth'    => 1,
  ) );
  echo '</ul>';
}

