<?php
/**
 * POST TYPES Y TAXONOMÍAS PARA HOTEL CASABLANCA
 * -----------------------------------------------
 * - Post Type: habitacion (independiente)
 * - Taxonomías: amenidad, grupo_amenidad
 * Con metacampos, iconos por clase (Phosphor) y menú propio en admin.
 */
if ( ! defined('ABSPATH') ) exit;

/**
 * ===========================================================
 * POST TYPE PERSONALIZADO: HABITACIONES
 * ===========================================================
 */
add_action('init', function () {

  // === POST TYPE: Habitaciones ===
  register_post_type('habitacion', [
    'labels' => [
      'name' => 'Habitaciones',
      'singular_name' => 'Habitación',
      'add_new' => 'Agregar Habitación',
      'add_new_item' => 'Agregar Nueva Habitación',
      'edit_item' => 'Editar Habitación',
      'new_item' => 'Nueva Habitación',
      'view_item' => 'Ver Habitación',
      'search_items' => 'Buscar Habitaciones',
      'not_found' => 'No se encontraron habitaciones',
      'not_found_in_trash' => 'No hay habitaciones en la papelera',
      'all_items' => 'Todas las Habitaciones',
      'menu_name' => 'Habitaciones'
    ],
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => ['slug' => 'habitaciones', 'with_front' => false],
    'capability_type' => 'post',
    'has_archive' => 'habitaciones',
    'hierarchical' => false,
    'menu_position' => 22,
    'menu_icon' => 'dashicons-building',
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
    'show_in_rest' => true,
  ]);

  // === TAXONOMÍA: Categorías de Habitación ===
  register_taxonomy('categoria_habitacion', ['habitacion'], [
    'labels' => [
      'name' => 'Categorías de Habitación',
      'singular_name' => 'Categoría',
      'add_new_item' => 'Agregar Categoría',
      'edit_item' => 'Editar Categoría',
      'all_items' => 'Todas las Categorías',
      'menu_name' => 'Categorías'
    ],
    'public' => true,
    'hierarchical' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'rewrite' => ['slug' => 'categoria-habitacion', 'with_front' => false],
    'show_in_rest' => true,
  ]);

  // === TAXONOMÍA: Amenidades ===
  register_taxonomy('amenidad', ['habitacion'], [
    'labels' => [
      'name' => 'Amenidades',
      'singular_name' => 'Amenidad',
      'add_new_item' => 'Agregar Amenidad',
      'edit_item' => 'Editar Amenidad',
      'all_items' => 'Todas las Amenidades',
      'menu_name' => 'Amenidades'
    ],
    'public' => false,
    'hierarchical' => false,
    'show_ui' => true,
    'show_admin_column' => false,
    'show_in_quick_edit' => false,
    'meta_box_cb' => false, // Quitar metabox automático
    'rewrite' => false,
    'show_in_rest' => true,
  ]);

  // === TAXONOMÍA: Grupo de Amenidad ===
  register_taxonomy('grupo_amenidad', ['amenidad'], [
    'labels' => [
      'name' => 'Grupos de Amenidad',
      'singular_name' => 'Grupo de Amenidad',
      'add_new_item' => 'Agregar Grupo',
      'edit_item' => 'Editar Grupo',
      'all_items' => 'Todos los Grupos',
      'menu_name' => 'Grupos de Amenidad'
    ],
    'public' => false,
    'hierarchical' => true,
    'show_ui' => true,
    'show_admin_column' => false,
    'rewrite' => false,
    'show_in_rest' => true,
  ]);
});

/**
 * ===========================================================
 * TEMPLATE HOOKS - ASEGURAR QUE SE USEN LOS TEMPLATES CORRECTOS
 * ===========================================================
 */
// Forzar template para habitaciones
add_filter('single_template', function($template) {
    global $post;
    if ($post->post_type === 'habitacion') {
        $child_template = get_stylesheet_directory() . '/single-habitacion.php';
        if (file_exists($child_template)) {
            return $child_template;
        }
    }
    return $template;
});

// Forzar template para archivo de habitaciones
add_filter('archive_template', function($template) {
    if (is_post_type_archive('habitacion')) {
        $child_template = get_stylesheet_directory() . '/archive-habitacion.php';
        if (file_exists($child_template)) {
            return $child_template;
        }
    }
    return $template;
});

/**
 * ===========================================================
 * FLUSH REWRITE RULES - FORZAR ACTUALIZACIÓN DE URLs
 * ===========================================================
 */
// Forzar actualización de permalinks cuando se activa el tema
add_action('after_switch_theme', function() {
  flush_rewrite_rules();
});

// También forzar cuando se carga wp-admin para asegurar que las reglas estén actualizadas
add_action('admin_init', function() {
  if (get_option('habitacion_rewrite_flushed') !== 'yes') {
    flush_rewrite_rules();
    update_option('habitacion_rewrite_flushed', 'yes');
  }
});

/**
 * ===========================================================
 * META BOXES PARA HABITACIONES (POST TYPE)
 * ===========================================================
 */
add_action('add_meta_boxes', function(){
  add_meta_box(
    'habitacion_detalles',
    'Detalles de la Habitación',
    'hotel_habitacion_meta_box',
    'habitacion',
    'normal',
    'high'
  );
});

function hotel_habitacion_meta_box($post) {
  wp_nonce_field('save_habitacion_meta', 'habitacion_meta_nonce');
  
  $abr     = get_post_meta($post->ID, 'hab_abreviatura', true);
  $img_back = get_post_meta($post->ID, 'hab_imagen_posterior', true);
  $gal     = get_post_meta($post->ID, 'hab_galeria_ids', true);
  $camas   = get_post_meta($post->ID, 'hab_camas', true);
  $precio  = get_post_meta($post->ID, 'hab_precio', true);
  $pers    = get_post_meta($post->ID, 'hab_personas', true);
  $notaC   = get_post_meta($post->ID, 'hab_nota_card', true);
  $notaF   = get_post_meta($post->ID, 'hab_nota_ficha', true);
  $descL   = get_post_meta($post->ID, 'hab_descripcion_larga', true);
  $amen    = (array) get_post_meta($post->ID, 'hab_amenidades', true);

  $amen_terms = get_terms(['taxonomy'=>'amenidad','hide_empty'=>false]);
  ?>
  <style>
    .hotel-meta img{max-width:160px;height:auto;display:block;margin:.5rem 0;border-radius:8px}
    .hotel-meta table th{width:150px;vertical-align:top;padding-top:8px;}
  </style>
  <div class="hotel-meta">
    <table class="form-table">
      <tr>
        <th><label for="hab_abreviatura">Abreviatura</label></th>
        <td><input type="text" name="hab_abreviatura" id="hab_abreviatura" value="<?php echo esc_attr($abr); ?>" placeholder="Es / Pa / P+" class="regular-text"></td>
      </tr>

      <tr>
        <th><label>Imagen posterior</label></th>
        <td>
          <input type="hidden" name="hab_imagen_posterior" id="hab_imagen_posterior" value="<?php echo esc_attr($img_back); ?>">
          <button type="button" class="button" id="hab_pick_img_back">Elegir imagen</button>
          <div id="hab_img_back_preview">
            <?php if ($img_back) echo wp_get_attachment_image($img_back,'medium'); ?>
          </div>
          <p class="description">Imagen secundaria para galería o vistas interiores.</p>
        </td>
      </tr>

      <tr>
        <th><label for="hab_galeria_ids">Galería (IDs)</label></th>
        <td>
          <input type="text" name="hab_galeria_ids" id="hab_galeria_ids" value="<?php echo esc_attr($gal); ?>" class="regular-text" placeholder="12,45,99">
          <p class="description">IDs separados por comas o usa el botón para seleccionar.</p>
          <button type="button" class="button" id="hab_pick_gallery">Elegir galería</button>
        </td>
      </tr>

      <tr>
        <th><label for="hab_camas">Distribución de camas</label></th>
        <td><input type="text" name="hab_camas" id="hab_camas" value="<?php echo esc_attr($camas); ?>" class="regular-text" placeholder="1 mat, 1KS"></td>
      </tr>

      <tr>
        <th><label for="hab_precio">Precio</label></th>
        <td><input type="number" name="hab_precio" id="hab_precio" value="<?php echo esc_attr($precio); ?>" step="1" min="0"> MXN</td>
      </tr>

      <tr>
        <th><label for="hab_personas">No. Personas</label></th>
        <td><input type="text" name="hab_personas" id="hab_personas" value="<?php echo esc_attr($pers); ?>" class="regular-text" placeholder="1 Persona / 1-2 Personas"></td>
      </tr>

      <tr>
        <th><label for="hab_nota_card">Nota para Card</label></th>
        <td><input type="text" name="hab_nota_card" id="hab_nota_card" value="<?php echo esc_attr($notaC); ?>" class="regular-text"></td>
      </tr>

      <tr>
        <th><label for="hab_nota_ficha">Nota para Ficha</label></th>
        <td><textarea name="hab_nota_ficha" id="hab_nota_ficha" rows="3" class="large-text"><?php echo esc_textarea($notaF); ?></textarea></td>
      </tr>

      <tr>
        <th><label for="hab_descripcion_larga">Descripción larga</label></th>
        <td>
          <textarea name="hab_descripcion_larga" id="hab_descripcion_larga" rows="5" class="large-text" placeholder="Describe la habitación, servicios, piso, vista, etc."><?php echo esc_textarea($descL); ?></textarea>
          <p class="description">Se puede mostrar debajo de la descripción corta.</p>
        </td>
      </tr>

      <tr>
        <th><label>Amenidades</label></th>
        <td>
          <?php
            if (!is_wp_error($amen_terms) && $amen_terms){
              foreach($amen_terms as $t){
                $checked = in_array($t->term_id, $amen) ? 'checked' : '';
                echo '<label style="display:inline-block;margin:.25rem 1rem .25rem 0"><input type="checkbox" name="hab_amenidades[]" value="'.esc_attr($t->term_id).'" '.$checked.'> '.esc_html($t->name).'</label>';
              }
            }else{
              echo '<em>No hay Amenidades aún.</em>';
            }
          ?>
        </td>
      </tr>
    </table>
  </div>
  <script>
  jQuery(function($){
    function pickSingle(cb){
      const frame = wp.media({multiple:false, library:{type:'image'}});
      frame.on('select', function(){
        const sel = frame.state().get('selection').toJSON();
        if (sel[0]) cb(sel[0]);
      });
      frame.open();
    }

    $('#hab_pick_img_back').on('click', function(e){
      e.preventDefault();
      pickSingle(function(file){
        $('#hab_imagen_posterior').val(file.id);
        $('#hab_img_back_preview').html('<img src="'+file.sizes.medium.url+'" alt="">');
      });
    });

    $('#hab_pick_gallery').on('click', function(e){
      e.preventDefault();
      const frame = wp.media({multiple:true, library:{type:'image'}});
      frame.on('select', function(){
        const sel = frame.state().get('selection').toJSON();
        $('#hab_galeria_ids').val(sel.map(i=>i.id).join(','));
      });
      frame.open();
    });
  });
  </script>
  <?php
}

// Guardar meta data del post type
add_action('save_post', function($post_id){
  if (!isset($_POST['habitacion_meta_nonce']) || !wp_verify_nonce($_POST['habitacion_meta_nonce'], 'save_habitacion_meta')) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (get_post_type($post_id) !== 'habitacion') {
    return;
  }

  $fields = [
    'hab_abreviatura',
    'hab_imagen_posterior',
    'hab_galeria_ids',
    'hab_camas',
    'hab_precio',
    'hab_personas',
    'hab_nota_card',
    'hab_nota_ficha',
    'hab_descripcion_larga',
  ];
  
  foreach($fields as $f){
    if(isset($_POST[$f])){
      $val = is_array($_POST[$f]) ? array_map('sanitize_text_field', $_POST[$f]) : sanitize_text_field($_POST[$f]);
      update_post_meta($post_id, $f, $val);
    }
  }
  
  // amenidades (array de term_ids)
  if(isset($_POST['hab_amenidades'])){
    $arr = array_map('intval', (array)$_POST['hab_amenidades']);
    update_post_meta($post_id, 'hab_amenidades', $arr);
  }else{
    delete_post_meta($post_id, 'hab_amenidades');
  }
});
add_action('amenidad_add_form_fields', function(){
  $grupos = get_terms([
    'taxonomy'   => 'grupo_amenidad',
    'hide_empty' => false,
  ]);
  ?>
  <div class="form-field">
    <label for="amenidad_icono">Nombre del ícono</label>
    <input type="text" name="amenidad_icono" id="amenidad_icono" placeholder="Ej: ph-wifi o ph-bed">
    <p class="description">Clase del ícono de <a href="https://phosphoricons.com/" target="_blank">Phosphor Icons</a> (o similar).</p>
  </div>

  <div class="form-field">
    <label for="amenidad_grupos">Grupos de Amenidad</label>
    <select name="amenidad_grupos[]" id="amenidad_grupos"
            multiple
            size="<?php echo min(6, max(3, is_array($grupos)? count($grupos): 3)); ?>">
      <?php if ( !is_wp_error($grupos) && $grupos ) :
        foreach($grupos as $g): ?>
          <option value="<?php echo esc_attr($g->term_id); ?>">
            <?php echo esc_html($g->name); ?>
          </option>
        <?php endforeach;
      endif; ?>
    </select>
    <p class="description">Mantén presionado Ctrl/Cmd para elegir varios (Principales, Seguridad, etc.).</p>
  </div>
  <?php
});

add_action('amenidad_edit_form_fields', function($term){
  $icono = get_term_meta($term->term_id, 'amenidad_icono', true);
  $grupos_sel = (array) get_term_meta($term->term_id, 'amenidad_grupos', true);

  $grupos = get_terms([
    'taxonomy'   => 'grupo_amenidad',
    'hide_empty' => false,
  ]);
  ?>
  <tr class="form-field">
    <th scope="row"><label for="amenidad_icono">Nombre del ícono</label></th>
    <td>
      <input type="text" name="amenidad_icono" id="amenidad_icono" class="regular-text"
             value="<?php echo esc_attr($icono); ?>" placeholder="Ej: ph-wifi o ph-bed">
      <p class="description">Clase del ícono (por ejemplo: <code>ph-wifi</code>).</p>
    </td>
  </tr>

  <tr class="form-field">
    <th scope="row"><label for="amenidad_grupos">Grupos de Amenidad</label></th>
    <td>
      <select name="amenidad_grupos[]" id="amenidad_grupos" multiple
              size="<?php echo min(6, max(3, is_array($grupos)? count($grupos): 3)); ?>">
        <?php if ( !is_wp_error($grupos) && $grupos ) :
          foreach($grupos as $g): ?>
            <option value="<?php echo esc_attr($g->term_id); ?>"
              <?php selected( in_array($g->term_id, array_map('intval',$grupos_sel), true) ); ?>>
              <?php echo esc_html($g->name); ?>
            </option>
          <?php endforeach;
        endif; ?>
      </select>
      <p class="description">Selecciona uno o varios grupos.</p>
    </td>
  </tr>
  <?php
});

add_action('created_amenidad', 'hotel_save_amenidad_meta_multi');
add_action('edited_amenidad',  'hotel_save_amenidad_meta_multi');
function hotel_save_amenidad_meta_multi($term_id){
  // Ícono
  if (isset($_POST['amenidad_icono'])){
    update_term_meta($term_id, 'amenidad_icono', sanitize_text_field($_POST['amenidad_icono']));
  }
  // Grupos (array de IDs)
  if (isset($_POST['amenidad_grupos']) && is_array($_POST['amenidad_grupos'])){
    $ids = array_values(array_unique(array_map('intval', $_POST['amenidad_grupos'])));
    update_term_meta($term_id, 'amenidad_grupos', $ids);
  } else {
    delete_term_meta($term_id, 'amenidad_grupos');
  }
}

/**
 * Columna "Grupo(s)" en el listado de Amenidades
 */
add_filter('manage_edit-amenidad_columns', function($columns){
  $columns['amenidad_grupos'] = 'Grupo(s)';
  return $columns;
});
add_filter('manage_amenidad_custom_column', function($out, $column, $term_id){
  if ($column === 'amenidad_grupos'){
    $ids = (array) get_term_meta($term_id, 'amenidad_grupos', true);
    if ($ids){
      $names = [];
      foreach(array_map('intval',$ids) as $gid){
        $t = get_term($gid, 'grupo_amenidad');
        if (!is_wp_error($t) && $t){
          $names[] = $t->name;
        }
      }
      $out = $names ? esc_html(implode(', ', $names)) : '—';
    } else {
      $out = '—';
    }
  }
  return $out;
}, 10, 3);

/**
 * ===========================================================
 * ENQUEUE ADMIN MEDIA
 * ===========================================================
 */
add_action('admin_enqueue_scripts', function($hook){
  global $post_type;
  if (($hook === 'post.php' || $hook === 'post-new.php') && $post_type === 'habitacion') {
    wp_enqueue_media();
    wp_enqueue_script('jquery');
  }
  if (false !== strpos($hook, 'edit-tags.php') || false !== strpos($hook,'term.php')) {
    wp_enqueue_media();
    wp_enqueue_script('jquery');
  }
});

