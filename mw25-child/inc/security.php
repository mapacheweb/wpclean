<?php
// /inc/security.php

if ( ! defined( 'ABSPATH' ) ) exit;

// Oculta la versión de WP en el <head>
add_filter('the_generator', '__return_empty_string');

// Bloquear enumeración ?author=1 (evita que saquen usuarios válidos)
add_action('init', function() {
  if (!is_admin() && isset($_REQUEST['author'])) {
    wp_redirect(home_url(), 301);
    exit;
  }
});

// Bloquear la exposición pública de /wp-json/wp/v2/users
add_filter('rest_endpoints', function($endpoints){
  if ( isset( $endpoints['/wp/v2/users'] ) ) {
    unset( $endpoints['/wp/v2/users'] );
  }
  if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
    unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
  }
  return $endpoints;
});

// Permitir sólo actualizaciones menores automáticas (parches de seguridad)
add_filter( 'auto_update_core', function( $update, $item ) {
  if ( isset($item->response) && $item->response === 'minor' ) {
    return true; // sí a 6.x.x -> 6.x.y
  }
  return false; // no saltar mayor sin revisar
}, 10, 2 );
