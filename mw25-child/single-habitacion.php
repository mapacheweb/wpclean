<?php
// single-habitacion.php - Ficha individual de habitación
if ( ! defined('ABSPATH') ) exit;

if ( ! function_exists('get_the_post_thumbnail_id') ) {
  require_once ABSPATH . WPINC . '/post-thumbnail-template.php';
}

get_header();

if (have_posts()) :
  the_post();
  $post_id = get_the_ID();
  
  // Obtener metadatos
  $img_id       = function_exists('get_the_post_thumbnail_id') ? get_the_post_thumbnail_id($post_id) : 0;
  if (!$img_id) {
    $img_id = (int) get_post_meta($post_id, '_thumbnail_id', true);
  }
  $img_back_id  = (int) get_post_meta($post_id, 'hab_imagen_posterior', true);
  $hero_img     = '';

  if ($img_back_id) {
    $hero_img = wp_get_attachment_image_url($img_back_id, 'xxl');
  }
  if (!$hero_img && $img_id) {
    $hero_img = wp_get_attachment_image_url($img_id, 'xxl');
  }
  if (!$hero_img) {
    $hero_img = get_theme_file_uri('assets/og-default.jpg');
  }

  $categorias = wp_get_post_terms($post_id, 'categoria_habitacion');
  $cat_name   = (!empty($categorias) && !is_wp_error($categorias)) ? $categorias[0]->name : 'Habitación';
  
  $gal_ids   = get_post_meta($post_id, 'hab_galeria_ids', true);
  $camas     = get_post_meta($post_id, 'hab_camas', true);
  $precio    = get_post_meta($post_id, 'hab_precio', true);
  $pers      = get_post_meta($post_id, 'hab_personas', true);
  $notaF     = get_post_meta($post_id, 'hab_nota_ficha', true);
  $notaC     = get_post_meta($post_id, 'hab_nota_card', true);
  $descL     = get_post_meta($post_id, 'hab_descripcion_larga', true);
  $amen_ids  = (array) get_post_meta($post_id, 'hab_amenidades', true);

  // ===== separar amenidades por grupo =====
  $amen_principales = [];
  $amen_secundarias = [];
  $amen_seguridad   = [];
  $amen_otros       = [];

  if ($amen_ids){
    foreach ($amen_ids as $aid){
      $amen_term = get_term($aid, 'amenidad');
      if ( is_wp_error($amen_term) || ! $amen_term ) continue;

      $icon   = get_term_meta($aid, 'amenidad_icono', true);
      $grupos = (array) get_term_meta($aid, 'amenidad_grupos', true);

      // por defecto lo metemos en otros
      $slot = 'otros';

      if ($grupos){
        // traemos los nombres y slugs de los grupos para decidir
        $nombres = [];
        $slugs   = [];
        foreach($grupos as $gid){
          $gterm = get_term($gid, 'grupo_amenidad');
          if ( ! is_wp_error($gterm) && $gterm ){
            $nombres[] = trim($gterm->name);
            $slugs[]   = sanitize_title($gterm->slug);
          }
        }
        $nombres_normalizados = array_map('strtolower', $nombres);
        $slugs_normalizados   = array_map('strtolower', $slugs);

        $es_principal = array_intersect($nombres_normalizados, ['amenidades principales','principales','principal'])
          || array_intersect($slugs_normalizados, ['principales','principal','amenidades-principales']);

        $es_secundaria = array_intersect($nombres_normalizados, ['amenidades secundarias','secundarias','secundaria'])
          || array_intersect($slugs_normalizados, ['secundarias','secundaria','amenidades-secundarias']);

        $es_seguridad = array_intersect($nombres_normalizados, ['seguridad e higiene','seguridad e higiene','salud y seguridad'])
          || array_intersect($slugs_normalizados, ['seguridad','seguridad-e-higiene','salud-y-seguridad']);

        if ($es_principal){
          $slot = 'principales';
        } elseif ($es_secundaria){
          $slot = 'secundarias';
        } elseif ($es_seguridad){
          $slot = 'seguridad';
        }
      }

      $item = [
        'id'   => $aid,
        'name' => $amen_term->name,
        'icon' => $icon,
      ];

      switch ($slot) {
        case 'principales': $amen_principales[] = $item; break;
        case 'secundarias': $amen_secundarias[] = $item; break;
        case 'seguridad':   $amen_seguridad[]   = $item; break;
        default:            $amen_otros[]       = $item; break;
      }
    }
  }

  // Preparar galería para PhotoSwipe
  $gallery_ids = [];
  if ($gal_ids) {
    if (is_string($gal_ids)) {
      $gallery_ids = array_map('intval', explode(',', $gal_ids));
    } elseif (is_array($gal_ids)) {
      $gallery_ids = array_map('intval', $gal_ids);
    }
    $gallery_ids = array_values(array_filter($gallery_ids));
  }

  $gallery_seen  = [];
  $build_gallery_item = function ($attachment_id, $thumb_size = 'large') use (&$gallery_seen) {
    $attachment_id = (int) $attachment_id;
    if (!$attachment_id || in_array($attachment_id, $gallery_seen, true)) {
      return null;
    }

    $full = wp_get_attachment_image_src($attachment_id, 'full');
    if (!$full) {
      return null;
    }
    $thumb = wp_get_attachment_image_src($attachment_id, $thumb_size);

    $gallery_seen[] = $attachment_id;

    return [
      'id'     => $attachment_id,
      'src'    => $full[0],
      'width'  => $full[1] ?: 1600,
      'height' => $full[2] ?: 900,
      'thumb'  => $thumb ? $thumb[0] : $full[0],
      'alt'    => get_post_meta($attachment_id, '_wp_attachment_image_alt', true) ?: get_the_title($attachment_id),
    ];
  };

  $gallery_main_item = null;
  $gallery_rest      = [];

  if ($img_id) {
    $item = $build_gallery_item($img_id, 'xxl');
    if ($item) {
      $gallery_main_item = $item;
    }
  }

  if ($gallery_ids) {
    foreach ($gallery_ids as $gid) {
      $item = $build_gallery_item($gid);
      if ($item) {
        $gallery_rest[] = $item;
      }
    }
  }

  if (!$gallery_main_item) {
    if ($gallery_rest) {
      $gallery_main_item = array_shift($gallery_rest);
    } else {
      $fallback = get_theme_file_uri('assets/og-default.jpg');
      $gallery_main_item = [
        'id'     => 0,
        'src'    => $fallback,
        'width'  => 1200,
        'height' => 630,
        'thumb'  => $fallback,
        'alt'    => get_the_title(),
      ];
    }
  }

  $hero_alt          = $gallery_main_item['alt'] ?: get_the_title();
  $thumb_limit       = 4;
  $rest_count        = count($gallery_rest);
  $additional_count  = max(0, $rest_count - $thumb_limit);
  $has_more_items    = $rest_count > $thumb_limit;
  ?>

<main id="site-main" class="hotel-wrap">
  <!-- ===== Hero superior ===== -->
  <header class="room-hero" style="background-image:url('<?php echo esc_url($hero_img); ?>')">
    <div class="room-hero__overlay"></div>
    <div class="room-hero__content container">
      <!-- <p class="room-hero__eyebrow">Habitaciones</p> -->
      <h1 class="room-hero__title"><?php the_title(); ?></h1>
      <?php
      $hero_lede = $descL ? esc_html($descL) : $notaC;
      if ( $hero_lede ) {
        echo '<p class="room-hero__lede">' . esc_html($hero_lede) . '</p>';
      }
      ?>
    </div>
  </header>

  <!-- ===== Galería principal ===== -->
  <section class="room-gallery-strip container pswp-gallery" data-pswp-gallery="habitacion-<?php echo esc_attr($post_id); ?>">
    <div class="room-gallery-strip__main">
      <a class="room-gallery-strip__main-link"
         href="<?php echo esc_url($gallery_main_item['src']); ?>"
         data-pswp-src="<?php echo esc_url($gallery_main_item['src']); ?>"
         data-pswp-width="<?php echo esc_attr($gallery_main_item['width']); ?>"
         data-pswp-height="<?php echo esc_attr($gallery_main_item['height']); ?>"
         data-pswp-caption="<?php echo esc_attr($hero_alt); ?>">
        <img src="<?php echo esc_url($gallery_main_item['thumb']); ?>" alt="<?php echo esc_attr($hero_alt); ?>">
      </a>
      <div class="room-gallery-strip__badge">
        <span class="badge-category">Vista Principal</span>
        <span class="badge-title"><?php echo esc_html(get_the_title()); ?></span>
      </div>
    </div>
    <div class="room-gallery-strip__thumbs">
      <?php if ($gallery_rest): ?>
        <?php foreach ($gallery_rest as $index => $item): ?>
          <?php
          $is_more_slot = ($has_more_items && $index === $thumb_limit - 1);

          if ($index < $thumb_limit) {
            if ($is_more_slot) {
              $more_label_text = sprintf(
                _n('Ver %d foto adicional', 'Ver %d fotos adicionales', $additional_count, 'mw25-child'),
                $additional_count
              );
              ?>
              <a class="thumb thumb--more"
                 href="<?php echo esc_url($item['src']); ?>"
                 data-pswp-src="<?php echo esc_url($item['src']); ?>"
                 data-pswp-width="<?php echo esc_attr($item['width']); ?>"
                 data-pswp-height="<?php echo esc_attr($item['height']); ?>"
                 data-pswp-caption="<?php echo esc_attr($item['alt']); ?>"
                 aria-label="<?php echo esc_attr($more_label_text); ?>">
                <img src="<?php echo esc_url($item['thumb']); ?>" alt="<?php echo esc_attr($item['alt']); ?>" loading="lazy">
                <span class="thumb-more__overlay">
                  <span class="thumb-more__count">+<?php echo esc_html($additional_count); ?></span>
                  <span class="thumb-more__label"><?php esc_html_e('Más', 'mw25-child'); ?></span>
                </span>
              </a>
              <?php
            } else {
              ?>
              <a class="thumb"
                 href="<?php echo esc_url($item['src']); ?>"
                 data-pswp-src="<?php echo esc_url($item['src']); ?>"
                 data-pswp-width="<?php echo esc_attr($item['width']); ?>"
                 data-pswp-height="<?php echo esc_attr($item['height']); ?>"
                 data-pswp-caption="<?php echo esc_attr($item['alt']); ?>">
                <img src="<?php echo esc_url($item['thumb']); ?>" alt="<?php echo esc_attr($item['alt']); ?>" loading="lazy">
              </a>
              <?php
            }
          } else {
            ?>
            <a class="thumb thumb--hidden"
               href="<?php echo esc_url($item['src']); ?>"
               data-pswp-src="<?php echo esc_url($item['src']); ?>"
               data-pswp-width="<?php echo esc_attr($item['width']); ?>"
               data-pswp-height="<?php echo esc_attr($item['height']); ?>"
               data-pswp-caption="<?php echo esc_attr($item['alt']); ?>"
               hidden></a>
            <?php
          }
          ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>



  <!-- ===== Layout de contenido ===== -->
  <section class="room-layout container">
    <div class="room-layout__left">

      <!-- ===== Información rápida ===== -->
      <?php if ($amen_principales): ?>
        <section class="room-quick-info">
          <div class="quick-info-grid">
            <?php foreach ($amen_principales as $a): ?>
              <div class="quick-info-item">
                <i class="ph <?php echo esc_attr($a['icon'] ?: 'ph-check-circle'); ?>"></i>
                <span><?php echo esc_html($a['name']); ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif; ?>
      <?php if ($notaF): ?>
        <p class="room-quick-note"><?php echo esc_html($notaF); ?></p>
      <?php endif; ?>

      <!-- descripción principal -->
      <article class="room-desc">
        <h2><?php the_title(); ?></h2>
        <?php
        $content = get_the_content(null, false, $post_id);
        if ($content) {
          echo '<div class="room-desc__text">' . apply_filters('the_content', $content) . '</div>';
        } elseif ($descL) {
          echo '<div class="room-desc__text">' . wpautop(esc_html($descL)) . '</div>';
        } elseif ($notaF) {
          echo '<div class="room-desc__text">' . wpautop(esc_html($notaF)) . '</div>';
        } else {
          echo '<p class="room-desc__fallback">' . esc_html__('Con el confort y la calidez que distinguen al Hotel Casablanca, estas habitaciones han sido diseñadas para ofrecer descanso y comodidad a cada huésped.', 'mw25-child') . '</p>';
        }
        ?>
      </article>

      

    </div>

    <!-- Columna derecha -->
    <aside class="room-layout__right">
      <div class="booking-card">
        <?php if ($precio): ?>
          <p class="booking-card__label">Desde</p>
          <p class="booking-card__price">MX$<?php echo number_format((float)$precio, 2, '.', ','); ?></p>
        <?php endif; ?>
        <p class="booking-card__meta">1 noche, <?php echo $pers ? esc_html($pers) : '2 adultos'; ?></p>
        <div class="booking-card__actions">
          <?php
          $wa_message = rawurlencode( sprintf( __('Hola, me interesa reservar %s', 'mw25-child'), get_the_title() ) );
          $wa_url     = 'https://wa.me/526181369761?text=' . $wa_message;
          ?>
          <a href="<?php echo esc_url($wa_url); ?>" class="btn btn-primary w-full"><i class="ph-bold ph-call-bell"></i> <?php esc_html_e('Reservar aquí', 'mw25-child'); ?></a>
          <a href="tel:+526188113599" class="btn btn-secondary w-full"><i class="ph-fill ph-phone-incoming"></i> <?php esc_html_e('Reservar por teléfono', 'mw25-child'); ?></a>
        </div>
        <a href="#" class="booking-card__link">Revisar términos y condiciones</a>
      </div>

      <?php if ($amen_secundarias): ?>
        <section class="amenities-side">
          <h3 class="amenities-side__title"><?php esc_html_e('Amenidades', 'mw25-child'); ?></h3>
          <ul class="amenities-side__grid">
            <?php foreach($amen_secundarias as $a): ?>
              <li class="amenities-side__item">
                <span class="amenities-side__icon">
                  <?php if (!empty($a['icon'])): ?>
                    <i class="ph <?php echo esc_attr($a['icon']); ?>"></i>
                  <?php else: ?>
                    <i class="ph ph-check-circle"></i>
                  <?php endif; ?>
                </span>
                <span class="amenities-side__label"><?php echo esc_html($a['name']); ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </section>
      <?php endif; ?>


      <!-- Amenidades organizadas -->
      <div class="room-amenities-blocks" id="amenidades">

        <?php if ($amen_seguridad): ?>
          <section class="amen-block">
            <h3>Seguridad e higiene</h3>
            <ul>
              <?php foreach($amen_seguridad as $a): ?>
                <li>
                  <?php if ($a['icon']): ?><i class="ph <?php echo esc_attr($a['icon']); ?>"></i><?php endif; ?>
                  <?php echo esc_html($a['name']); ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </section>
        <?php endif; ?>

      </div>
      
    </aside>
  </section>
</main>

<?php 
else :
  ?>
  <main id="site-main" class="hotel-wrap">
    <section class="container" style="padding:4rem 1.5rem;text-align:center;">
      <h1>Habitación no disponible</h1>
      <p>Lo sentimos, no encontramos la habitación solicitada. Intenta con otra categoría.</p>
      <p><a class="btn" href="/habitaciones/">Volver al listado de habitaciones</a></p>
    </section>
  </main>
  <?php
endif;

get_footer(); 
?>
