<?php
/**
 * CREAR HABITACIONES DE EJEMPLO
 * Ejecutar una sola vez para crear contenido de prueba
 */

if ( ! defined('ABSPATH') ) exit;

function crear_habitaciones_ejemplo() {
  
  // Crear categorías primero
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
  
  // Crear grupos de amenidades
  $grupos_amen = [
    'Amenidades Principales',
    'Amenidades Secundarias', 
    'Seguridad e Higiene'
  ];
  
  foreach ($grupos_amen as $grupo) {
    if (!term_exists($grupo, 'grupo_amenidad')) {
      wp_insert_term($grupo, 'grupo_amenidad');
    }
  }
  
  // Crear algunas amenidades de ejemplo
  $amenidades = [
    ['name' => 'WiFi sin costo', 'icon' => 'ph-wifi', 'grupo' => 'Amenidades Principales'],
    ['name' => 'TV por cable', 'icon' => 'ph-television', 'grupo' => 'Amenidades Principales'],
    ['name' => 'Aire acondicionado', 'icon' => 'ph-snowflake', 'grupo' => 'Amenidades Principales'],
    ['name' => 'Cafetera en habitación', 'icon' => 'ph-coffee', 'grupo' => 'Amenidades Secundarias'],
    ['name' => 'Dos aguas de cortesía', 'icon' => 'ph-drop', 'grupo' => 'Amenidades Secundarias'],
    ['name' => 'Limpieza diaria', 'icon' => 'ph-broom', 'grupo' => 'Seguridad e Higiene'],
    ['name' => 'Desinfección sanitaria', 'icon' => 'ph-first-aid', 'grupo' => 'Seguridad e Higiene']
  ];
  
  $amen_ids = [];
  foreach ($amenidades as $amen) {
    if (!term_exists($amen['name'], 'amenidad')) {
      $term = wp_insert_term($amen['name'], 'amenidad');
      if (!is_wp_error($term)) {
        $term_id = $term['term_id'];
        // Agregar ícono
        update_term_meta($term_id, 'amenidad_icono', $amen['icon']);
        
        // Asignar grupo
        $grupo_term = get_term_by('name', $amen['grupo'], 'grupo_amenidad');
        if ($grupo_term) {
          update_term_meta($term_id, 'amenidad_grupos', [$grupo_term->term_id]);
        }
        
        $amen_ids[] = $term_id;
      }
    } else {
      $term = get_term_by('name', $amen['name'], 'amenidad');
      if ($term) {
        $amen_ids[] = $term->term_id;
      }
    }
  }
  
  // Habitación Estándar
  $hab_estandar = [
    'post_title' => 'Habitación Estándar',
    'post_content' => 'Nuestra categoría más sencilla con el confort y la calidez que distinguen al Hotel Casablanca.',
    'post_status' => 'publish',
    'post_type' => 'habitacion'
  ];
  
  if (empty(get_posts(['post_type' => 'habitacion', 'title' => 'Habitación Estándar', 'post_status' => 'any']))) {
    $post_id = wp_insert_post($hab_estandar);
    
    if (!is_wp_error($post_id)) {
      // Metadatos
      update_post_meta($post_id, 'hab_abreviatura', 'ES');
      update_post_meta($post_id, 'hab_camas', '1 cama matrimonial');
      update_post_meta($post_id, 'hab_precio', '810');
      update_post_meta($post_id, 'hab_personas', '1 noche, 2 adultos');
      update_post_meta($post_id, 'hab_nota_card', 'Cómoda y acogedora');
      update_post_meta($post_id, 'hab_nota_ficha', 'Perfecta para estancias cortas');
      update_post_meta($post_id, 'hab_descripcion_larga', 'Disfruta de espacios amplios, cuidadosamente equipados con todas las amenidades necesarias para garantizar la comodidad del huésped.');
      update_post_meta($post_id, 'hab_amenidades', $amen_ids);
      
      // Asignar categoría
      $cat_term = get_term_by('slug', 'estandar', 'categoria_habitacion');
      if ($cat_term) {
        wp_set_post_terms($post_id, [$cat_term->term_id], 'categoria_habitacion');
      }
    }
  }
  
  return 'Habitaciones de ejemplo creadas correctamente.';
}

// Agregar al menú de herramientas
add_action('admin_menu', function() {
  add_management_page(
    'Crear Ejemplos',
    'Crear Ejemplos', 
    'manage_options',
    'crear-ejemplos',
    function() {
      echo '<div class="wrap">';
      echo '<h1>Crear Habitaciones de Ejemplo</h1>';
      
      if (isset($_POST['crear_ejemplos'])) {
        echo '<div class="notice notice-success"><p>' . crear_habitaciones_ejemplo() . '</p></div>';
      }
      
      echo '<form method="post">';
      echo '<p>Crear habitaciones, amenidades y categorías de ejemplo para probar el sistema.</p>';
      echo '<input type="submit" name="crear_ejemplos" class="button button-primary" value="Crear Ejemplos">';
      echo '</form>';
      echo '</div>';
    }
  );
});