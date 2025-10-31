<?php
// archive-habitacion.php - Listado de habitaciones (/habitaciones)
if ( ! defined('ABSPATH') ) exit;
get_header();
?>

<div class="hotel-wrap">
  <header class="hero-habitaciones">
    <div class="inner">
      <p class="eyebrow">Nuestras habitaciones</p>
      <h1>Elige la habitación ideal</h1>
      <p class="lead">Opciones para viajes de trabajo, familia o estancias largas.</p>
    </div>
  </header>

  <div class="container rooms-groups" style="padding:2.5rem 1.5rem 4rem;">
    <?php
    // Obtener todas las habitaciones agrupadas por categoría
    $habitaciones = get_posts([
      'post_type' => 'habitacion',
      'post_status' => 'publish',
      'numberposts' => -1,
      'orderby' => 'title',
      'order' => 'ASC'
    ]);

    // Agrupar por categorías
    $groups = [
      'estandar'   => [],
      'panoramica' => [],
      'plus'       => [],
      'otras'      => [],
    ];

    if ($habitaciones) {
      foreach($habitaciones as $hab) {
        $categorias = wp_get_post_terms($hab->ID, 'categoria_habitacion');
        $cat_slug = 'otras';
        
        if (!empty($categorias) && !is_wp_error($categorias)) {
          $cat_slug = $categorias[0]->slug;
        }
        
        if (!isset($groups[$cat_slug])) {
          $groups[$cat_slug] = [];
        }
        $groups[$cat_slug][] = $hab;
      }
    }

    $orden = [
      'estandar'   => 'Habitaciones Estándar',
      'panoramica' => 'Habitaciones Panorámicas',
      'plus'       => 'Habitaciones Plus',
      'otras'      => 'Otras habitaciones',
    ];

    foreach($orden as $slug => $titulo) {
      $list = isset($groups[$slug]) ? $groups[$slug] : [];
      if (!$list) continue;
      ?>
      <section class="rooms-section">
        <h2 class="rooms-section__title"><?php echo esc_html($titulo); ?></h2>
        <div class="rooms-grid">
          <?php foreach($list as $hab):
            $img_id = get_the_post_thumbnail_id($hab->ID);
            $img    = $img_id ? wp_get_attachment_image_url($img_id,'large') : get_theme_file_uri('og-default.jpg');
            $precio = get_post_meta($hab->ID, 'hab_precio', true);
            $pers   = get_post_meta($hab->ID, 'hab_personas', true);
            $camas  = get_post_meta($hab->ID, 'hab_camas', true);
            $notaC  = get_post_meta($hab->ID, 'hab_nota_card', true);
            $link   = get_permalink($hab->ID);
            ?>
            <article class="room-card">
              <a href="<?php echo esc_url($link); ?>" class="room-card__media">
                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($hab->post_title); ?>">
                <?php if ($notaC): ?><span class="room-card__badge"><?php echo esc_html($notaC); ?></span><?php endif; ?>
              </a>
              <div class="room-card__body">
                <h3 class="room-card__title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($hab->post_title); ?></a></h3>
                <div class="room-card__meta">
                  <?php if ($pers): ?><span class="chip"><?php echo esc_html($pers); ?></span><?php endif; ?>
                  <?php if ($camas): ?><span class="chip"><?php echo esc_html($camas); ?></span><?php endif; ?>
                </div>
                <?php if ($precio): ?>
                  <p class="room-card__price">Desde <strong>$<?php echo number_format((float)$precio,0,'.',','); ?> MXN</strong></p>
                <?php endif; ?>
                <a href="<?php echo esc_url($link); ?>" class="btn btn-sm">Ver detalles</a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </section>
      <?php
    }
    ?>
  </div>
</div>

<?php get_footer(); ?>
