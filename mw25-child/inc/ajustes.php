<?php
// Ajustes generales para el child theme
if ( ! defined( 'ABSPATH' ) ) exit;

// Localiza las plantillas PHP del CPT "habitacion" para evitar el fallback a plantillas de bloques
add_filter('single_template', function ($single) {
  if ( is_singular('habitacion') ) {
    $file = get_stylesheet_directory() . '/single-habitacion.php';
    if ( file_exists($file) ) {
      return $file;
    }
  }
  return $single;
}, 20);

add_filter('archive_template', function ($archive) {
  if ( is_post_type_archive('habitacion') ) {
    $file = get_stylesheet_directory() . '/archive-habitacion.php';
    if ( file_exists($file) ) {
      return $file;
    }
  }
  return $archive;
}, 20);
