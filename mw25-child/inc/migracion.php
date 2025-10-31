<?php
/**
 * SCRIPT DE MIGRACIÓN: TAXONOMÍA A POST TYPE
 * 
 * Este script migra habitaciones de la taxonomía anterior
 * al nuevo post type 'habitacion'
 * 
 * EJECUTAR SOLO UNA VEZ desde wp-admin/tools.php
 */

if ( ! defined('ABSPATH') ) exit;

function migrar_habitaciones_taxonomia_a_post_type() {
  
  // 1. Crear categorías de habitación predeterminadas
  $categorias = [
    ['name' => 'Estándar', 'slug' => 'estandar'],
    ['name' => 'Panorámica', 'slug' => 'panoramica'], 
    ['name' => 'Plus', 'slug' => 'plus']
  ];
  
  foreach ($categorias as $cat) {
    if (!term_exists($cat['slug'], 'categoria_habitacion')) {
      wp_insert_term($cat['name'], 'categoria_habitacion', ['slug' => $cat['slug']]);
    }
  }
  
  // 2. Obtener términos de la taxonomía anterior 'habitacion'
  $old_terms = get_terms([
    'taxonomy' => 'habitacion',
    'hide_empty' => false
  ]);
  
  if (is_wp_error($old_terms) || empty($old_terms)) {
    return 'No se encontraron habitaciones en la taxonomía anterior.';
  }
  
  $migradas = 0;
  
  foreach ($old_terms as $term) {
    
    // Verificar si ya existe un post con este título
    $existing = get_posts([
      'post_type' => 'habitacion',
      'title' => $term->name,
      'post_status' => 'any',
      'numberposts' => 1
    ]);
    
    if (!empty($existing)) {
      continue; // Ya existe, saltar
    }
    
    // Crear nuevo post
    $post_data = [
      'post_title' => $term->name,
      'post_content' => $term->description ?: '',
      'post_status' => 'publish',
      'post_type' => 'habitacion'
    ];
    
    $post_id = wp_insert_post($post_data);
    
    if (is_wp_error($post_id)) {
      continue;
    }
    
    // Migrar metadatos
    $meta_fields = [
      'hab_abreviatura',
      'hab_imagen_id', 
      'hab_imagen_posterior',
      'hab_galeria_ids',
      'hab_camas',
      'hab_precio',
      'hab_personas',
      'hab_nota_card',
      'hab_nota_ficha',
      'hab_descripcion_larga',
      'hab_categoria',
      'hab_amenidades'
    ];
    
    foreach ($meta_fields as $field) {
      $value = get_term_meta($term->term_id, $field, true);
      if ($value) {
        update_post_meta($post_id, $field, $value);
      }
    }
    
    // Asignar imagen destacada si existe
    $img_id = get_term_meta($term->term_id, 'hab_imagen_id', true);
    if ($img_id) {
      set_post_thumbnail($post_id, $img_id);
    }
    
    // Asignar categoría basada en metadato hab_categoria
    $cat_hab = get_term_meta($term->term_id, 'hab_categoria', true);
    if ($cat_hab) {
      $cat_term = get_term_by('slug', $cat_hab, 'categoria_habitacion');
      if ($cat_term) {
        wp_set_post_terms($post_id, [$cat_term->term_id], 'categoria_habitacion');
      }
    }
    
    $migradas++;
  }
  
  return "Migración completada. {$migradas} habitaciones migradas.";
}

// Agregar herramienta de migración en admin
add_action('admin_menu', function() {
  add_management_page(
    'Migrar Habitaciones',
    'Migrar Habitaciones', 
    'manage_options',
    'migrar-habitaciones',
    function() {
      echo '<div class="wrap">';
      echo '<h1>Migrar Habitaciones de Taxonomía a Post Type</h1>';
      
      if (isset($_POST['ejecutar_migracion'])) {
        echo '<div class="notice notice-success"><p>' . migrar_habitaciones_taxonomia_a_post_type() . '</p></div>';
      }
      
      echo '<form method="post">';
      echo '<p>Esta herramienta migrará las habitaciones de la taxonomía anterior al nuevo post type.</p>';
      echo '<p><strong>IMPORTANTE:</strong> Ejecutar solo una vez.</p>';
      echo '<input type="submit" name="ejecutar_migracion" class="button button-primary" value="Ejecutar Migración">';
      echo '</form>';
      echo '</div>';
    }
  );
});