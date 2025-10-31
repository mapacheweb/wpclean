<?php
/**
 * Post type y taxonomías para Hotel Casablanca.
 */
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

const MM_HABITACION_REWRITE_VERSION = '20240419';

/**
 * Registra el post type "habitacion".
 */
function mm_register_habitacion_post_type() {
  $labels = array(
    'name'               => 'Habitaciones',
    'singular_name'      => 'Habitación',
    'add_new'            => 'Agregar Habitación',
    'add_new_item'       => 'Agregar Nueva Habitación',
    'edit_item'          => 'Editar Habitación',
    'new_item'           => 'Nueva Habitación',
    'view_item'          => 'Ver Habitación',
    'search_items'       => 'Buscar Habitaciones',
    'not_found'          => 'No se encontraron habitaciones',
    'not_found_in_trash' => 'No hay habitaciones en la papelera',
    'all_items'          => 'Todas las Habitaciones',
    'menu_name'          => 'Habitaciones',
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array(
      'slug'       => 'habitaciones',
      'with_front' => false,
      'feeds'      => false,
    ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => 22,
    'menu_icon'          => 'dashicons-building',
    'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    'show_in_rest'       => true,
  );

  register_post_type( 'habitacion', $args );
}
add_action( 'init', 'mm_register_habitacion_post_type' );

/**
 * Registra las taxonomías relacionadas con habitaciones.
 */
function mm_register_habitacion_taxonomies() {
  register_taxonomy(
    'categoria_habitacion',
    array( 'habitacion' ),
    array(
      'labels' => array(
        'name'          => 'Categorías de Habitación',
        'singular_name' => 'Categoría',
        'add_new_item'  => 'Agregar Categoría',
        'edit_item'     => 'Editar Categoría',
        'all_items'     => 'Todas las Categorías',
        'menu_name'     => 'Categorías',
      ),
      'public'            => true,
      'hierarchical'      => true,
      'show_ui'           => true,
      'show_admin_column' => true,
      'rewrite'           => array(
        'slug'         => 'habitaciones/categoria',
        'with_front'   => false,
        'hierarchical' => true,
      ),
      'show_in_rest'      => true,
    )
  );

  register_taxonomy(
    'amenidad',
    array( 'habitacion' ),
    array(
      'labels' => array(
        'name'          => 'Amenidades',
        'singular_name' => 'Amenidad',
        'add_new_item'  => 'Agregar Amenidad',
        'edit_item'     => 'Editar Amenidad',
        'all_items'     => 'Todas las Amenidades',
        'menu_name'     => 'Amenidades',
      ),
      'public'           => false,
      'hierarchical'     => false,
      'show_ui'          => true,
      'show_admin_column'=> false,
      'show_in_quick_edit' => false,
      'meta_box_cb'      => false,
      'rewrite'          => false,
      'show_in_rest'     => true,
    )
  );

  register_taxonomy(
    'grupo_amenidad',
    array( 'habitacion' ),
    array(
      'labels' => array(
        'name'          => 'Grupos de Amenidad',
        'singular_name' => 'Grupo de Amenidad',
        'add_new_item'  => 'Agregar Grupo',
        'edit_item'     => 'Editar Grupo',
        'all_items'     => 'Todos los Grupos',
        'menu_name'     => 'Grupos de Amenidad',
      ),
      'public'       => false,
      'hierarchical' => true,
      'show_ui'      => true,
      'meta_box_cb'  => false,
      'rewrite'      => false,
      'show_in_rest' => true,
    )
  );
}
add_action( 'init', 'mm_register_habitacion_taxonomies', 11 );

/**
 * Plantillas personalizadas para habitaciones.
 */
function mm_habitacion_single_template( $template ) {
  if ( is_singular( 'habitacion' ) ) {
    $child_template = get_stylesheet_directory() . '/single-habitacion.php';
    if ( file_exists( $child_template ) ) {
      return $child_template;
    }
  }
  return $template;
}
add_filter( 'single_template', 'mm_habitacion_single_template' );

function mm_habitacion_archive_template( $template ) {
  if ( is_post_type_archive( 'habitacion' ) ) {
    $child_template = get_stylesheet_directory() . '/archive-habitacion.php';
    if ( file_exists( $child_template ) ) {
      return $child_template;
    }
  }
  return $template;
}
add_filter( 'archive_template', 'mm_habitacion_archive_template' );

/**
 * Asegura que las reglas de reescritura estén actualizadas.
 */
function mm_flush_habitacion_rewrite() {
  mm_register_habitacion_post_type();
  mm_register_habitacion_taxonomies();
  flush_rewrite_rules();
  update_option( 'mm_habitacion_rewrite_version', MM_HABITACION_REWRITE_VERSION );
}

add_action( 'after_switch_theme', 'mm_flush_habitacion_rewrite' );

function mm_maybe_flush_habitacion_rewrite() {
  if ( get_option( 'mm_habitacion_rewrite_version' ) !== MM_HABITACION_REWRITE_VERSION ) {
    flush_rewrite_rules();
    update_option( 'mm_habitacion_rewrite_version', MM_HABITACION_REWRITE_VERSION );
  }
}
add_action( 'init', 'mm_maybe_flush_habitacion_rewrite', 20 );
