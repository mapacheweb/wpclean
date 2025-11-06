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

function mw25_render_page_meta_box($post) {
  wp_nonce_field('mw25_save_page_meta', 'mw25_page_meta_nonce');

  $subtitle    = get_post_meta($post->ID, 'page_subtitle', true);
  $description = get_post_meta($post->ID, 'page_description', true);
  ?>
  <table class="form-table">
    <tr>
      <th scope="row"><label for="page_subtitle"><?php esc_html_e('Subtítulo', 'mw25-child'); ?></label></th>
      <td>
        <input type="text" class="regular-text" id="page_subtitle" name="page_subtitle" value="<?php echo esc_attr($subtitle); ?>">
        <p class="description"><?php esc_html_e('Texto breve que aparece debajo del título principal.', 'mw25-child'); ?></p>
      </td>
    </tr>
    <tr>
      <th scope="row"><label for="page_description"><?php esc_html_e('Descripción de página', 'mw25-child'); ?></label></th>
      <td>
        <textarea class="large-text" rows="4" id="page_description" name="page_description"><?php echo esc_textarea($description); ?></textarea>
        <p class="description"><?php esc_html_e('Mensaje descriptivo que se mostrará en el hero de la plantilla.', 'mw25-child'); ?></p>
      </td>
    </tr>
  </table>
  <?php
}

add_action('add_meta_boxes', function ($post_type, $post) {
  if ($post_type !== 'page') {
    return;
  }

  add_meta_box(
    'mw25_page_meta',
    __('Opciones de la página', 'mw25-child'),
    'mw25_render_page_meta_box',
    'page',
    'normal',
    'high',
    [
      '__block_editor_compatible_meta_box' => true,
      '__back_compat_meta_box'            => true,
    ]
  );
}, 10, 2);

add_action('save_post_page', function ($post_id) {
  if (!isset($_POST['mw25_page_meta_nonce']) || !wp_verify_nonce($_POST['mw25_page_meta_nonce'], 'mw25_save_page_meta')) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  if (!current_user_can('edit_page', $post_id)) {
    return;
  }

  $subtitle = isset($_POST['page_subtitle']) ? sanitize_text_field(wp_unslash($_POST['page_subtitle'])) : '';
  $description = isset($_POST['page_description']) ? sanitize_textarea_field(wp_unslash($_POST['page_description'])) : '';

  update_post_meta($post_id, 'page_subtitle', $subtitle);
  update_post_meta($post_id, 'page_description', $description);
});
