<?php
// Bloque principal: incluir modularmente cada parte del tema hijo

require_once get_stylesheet_directory() . '/inc/setup.php';
require_once get_stylesheet_directory() . '/inc/social-meta.php';
require_once get_stylesheet_directory() . '/inc/security.php';
require_once get_stylesheet_directory() . '/inc/ajustes.php';
require_once get_stylesheet_directory() . '/inc/taxonomia.php';
require_once get_stylesheet_directory() . '/inc/migracion.php';
require_once get_stylesheet_directory() . '/inc/ejemplos.php';




if ( ! defined('ABSPATH') ) exit;

add_shortcode('hotel_listado_habitaciones', function(){
  $habitaciones = get_posts([
    'post_type' => 'habitacion',
    'post_status' => 'publish',
    'numberposts' => -1,
    'orderby' => 'title',
    'order' => 'ASC'
  ]);
  
  if (!$habitaciones) return '<p>No hay habitaciones aún.</p>';

  ob_start(); ?>
  <div class="container grid grid-3">
    <?php foreach($habitaciones as $hab):
      $img_id = get_the_post_thumbnail_id($hab->ID);
      $img = $img_id ? wp_get_attachment_image_url($img_id,'large') : get_theme_file_uri('assets/og-default.jpg');
      $precio = get_post_meta($hab->ID,'hab_precio',true);
      $pers   = get_post_meta($hab->ID,'hab_personas',true);
      $camas  = get_post_meta($hab->ID,'hab_camas',true);
      $notaC  = get_post_meta($hab->ID,'hab_nota_card',true);
      $link   = get_permalink($hab->ID);
    ?>
      <a class="card room-card fade-in" href="<?php echo esc_url($link); ?>">
        <div class="media"><img src="<?php echo esc_url($img); ?>" alt=""></div>
        <div style="padding:1rem">
          <h3><?php echo esc_html($hab->post_title); ?></h3>
          <?php if($notaC): ?><p class="badge"><?php echo esc_html($notaC); ?></p><?php endif; ?>
          <div class="room-meta" style="margin-top:.5rem">
            <?php if($pers):  ?><span class="chip"><?php echo esc_html($pers); ?></span><?php endif; ?>
            <?php if($camas): ?><span class="chip"><?php echo esc_html($camas); ?></span><?php endif; ?>
            <?php if($precio):?><span class="chip">Desde $<?php echo number_format((float)$precio,0,'.',','); ?> MXN</span><?php endif; ?>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
  <?php
  return ob_get_clean();
});
