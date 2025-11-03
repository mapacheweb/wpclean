<?php
// archive-habitacion.php - Listado de habitaciones (/habitaciones)
if ( ! defined('ABSPATH') ) exit;
if ( ! function_exists('get_the_post_thumbnail_id') ) {
  require_once ABSPATH . WPINC . '/post-thumbnail-template.php';
}
get_header();
?>

<main id="site-main" class="hotel-wrap">
  <header class="rooms-hero">
    <div class="rooms-hero__overlay"></div>
    <div class="rooms-hero__content container">
      <p class="rooms-hero__eyebrow">Habitaciones</p>
      <h1 class="rooms-hero__title">Elige la habitación ideal</h1>
      <p class="rooms-hero__lede">Nuestras habitaciones amplias y cómodas ofrecen todas las amenidades necesarias para una estancia placentera.</p>
    </div>
  </header>

  <section class="rooms-lead">
    <div class="container">
      <p class="rooms-lead__copy">Más que un viaje, una experiencia que recordarás</p>
    </div>
  </section>

  <div class="rooms-groups container">
    <?php
    $habitaciones = get_posts([
      'post_type'      => 'habitacion',
      'post_status'    => 'publish',
      'numberposts'    => -1,
      'orderby'        => 'title',
      'order'          => 'ASC',
    ]);

    $get_room_image = function ($post_id, $size = 'large') {
      $img_id = function_exists('get_the_post_thumbnail_id') ? get_the_post_thumbnail_id($post_id) : 0;
      if (!$img_id) {
        $img_id = (int) get_post_meta($post_id, '_thumbnail_id', true);
      }

      if ($img_id) {
        $img = wp_get_attachment_image_url($img_id, $size);
        if ($img) {
          return $img;
        }
      }

      $img_back = (int) get_post_meta($post_id, 'hab_imagen_posterior', true);
      if ($img_back) {
        $img = wp_get_attachment_image_url($img_back, $size);
        if ($img) {
          return $img;
        }
      }

      return get_theme_file_uri('assets/og-default.jpg');
    };

    $get_room_principal_specs = function ($post_id, $limit = 3) {
      $amen_ids = (array) get_post_meta($post_id, 'hab_amenidades', true);
      if (!$amen_ids) {
        return [];
      }

      $specs = [];
      $seen  = [];

      foreach ($amen_ids as $aid) {
        $aid = (int) $aid;
        if (!$aid || in_array($aid, $seen, true)) {
          continue;
        }
        $seen[] = $aid;

        $amen_term = get_term($aid, 'amenidad');
        if (is_wp_error($amen_term) || !$amen_term) {
          continue;
        }

        $icon   = get_term_meta($aid, 'amenidad_icono', true);
        $grupos = (array) get_term_meta($aid, 'amenidad_grupos', true);

        $is_principal = false;

        if ($grupos) {
          $nombres = [];
          $slugs   = [];

          foreach ($grupos as $gid) {
            $gterm = get_term($gid, 'grupo_amenidad');
            if (!is_wp_error($gterm) && $gterm) {
              $nombres[] = strtolower(trim($gterm->name));
              $slugs[]   = strtolower(sanitize_title($gterm->slug));
            }
          }

          $is_principal = array_intersect($nombres, ['amenidades principales','principales','principal'])
            || array_intersect($slugs, ['principales','principal','amenidades-principales','amenidad-card']);
        }

        if ($is_principal) {
          $specs[] = [
            'name' => $amen_term->name,
            'icon' => $icon ?: 'ph-check-circle',
          ];
        }

        if (count($specs) >= $limit) {
          break;
        }
      }

      return $specs;
    };

    $get_room_group_terms = function ($post_id, $target_slugs = [], $limit = null) {
      $amen_ids = (array) get_post_meta($post_id, 'hab_amenidades', true);
      if (!$amen_ids) {
        return [];
      }

      $target_slugs = $target_slugs ?: ['amenidad-card'];
      $target_map = [];
      foreach ($target_slugs as $slug) {
        if ($slug) {
          $target_map[] = strtolower(sanitize_title($slug));
        }
      }
      $target_map = array_unique(array_filter($target_map));

      $results = [];
      $seen    = [];

      foreach ($amen_ids as $aid) {
        $aid = (int) $aid;
        if (!$aid || in_array($aid, $seen, true)) {
          continue;
        }
        $seen[] = $aid;

        $amen_term = get_term($aid, 'amenidad');
        if (is_wp_error($amen_term) || !$amen_term) {
          continue;
        }

        $grupos = (array) get_term_meta($aid, 'amenidad_grupos', true);
        if (!$grupos) {
          continue;
        }

        $belongs = false;
        foreach ($grupos as $gid) {
          $gterm = get_term($gid, 'grupo_amenidad');
          if (is_wp_error($gterm) || !$gterm) {
            continue;
          }
          $candidates = [
            strtolower(sanitize_title($gterm->slug)),
            strtolower(sanitize_title($gterm->name)),
          ];
          foreach ($candidates as $candidate) {
            if ($candidate && in_array($candidate, $target_map, true)) {
              $belongs = true;
              break 2;
            }
          }
        }

        if ($belongs) {
          $results[] = $amen_term->name;
        }

        if ($limit && count($results) >= $limit) {
          break;
        }
      }

      return $results;
    };

    $groups = [];

    if ($habitaciones) {
      foreach ($habitaciones as $hab) {
        $categorias = wp_get_post_terms($hab->ID, 'categoria_habitacion');
        $cat_slug = 'otras';
        $cat_name = __('Otras habitaciones', 'mw25-child');

        if (!empty($categorias) && !is_wp_error($categorias)) {
          $cat_slug = $categorias[0]->slug ?: 'otras';
          $cat_name = $categorias[0]->name ?: $cat_name;
        }

        if (!isset($groups[$cat_slug])) {
          $groups[$cat_slug] = [
            'title' => $cat_name,
            'items' => [],
          ];
        }

        $groups[$cat_slug]['items'][] = $hab;
      }
    }

    $orden = [
      'estandar'   => __('Habitación Estándar', 'mw25-child'),
      'panoramica' => __('Habitación Panorámica', 'mw25-child'),
      'plus'       => __('Habitaciones Piso Plus', 'mw25-child'),
    ];

    foreach ($orden as $slug => $titulo_default) {
      if (empty($groups[$slug]['items'])) {
        continue;
      }

      $section_title = $groups[$slug]['title'] ?: $titulo_default;
      ?>
      <section class="rooms-section">
        <h2 class="rooms-section__title"><?php echo esc_html($section_title); ?></h2>
        <div class="rooms-grid">
          <?php foreach ($groups[$slug]['items'] as $hab):
            $img     = $get_room_image($hab->ID, 'large');
            $precio  = get_post_meta($hab->ID, 'hab_precio', true);
            $pers    = get_post_meta($hab->ID, 'hab_personas', true);
            $camas   = get_post_meta($hab->ID, 'hab_camas', true);
            $notaC   = get_post_meta($hab->ID, 'hab_nota_card', true);
            $abrevia = get_post_meta($hab->ID, 'hab_abreviatura', true);
            $card_amenities = $get_room_group_terms($hab->ID, ['amenidad-card','amenidad card','amenidades-card','card'], 4);
            ?>
            <article class="room-cardv2">
              <a class="room-cardv2__media" href="<?php echo esc_url(get_permalink($hab->ID)); ?>">
                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($hab->post_title); ?>">
                
                <?php if ($abrevia): ?>
                  <span class="room-cardv2__tag"><?php echo esc_html($abrevia); ?></span>
                <?php endif; ?>
              </a>
              <div class="room-cardv2__body">
                <header class="room-cardv2__header">
                  <h3 class="room-cardv2__title">
                    <?php
                  if ($notaC) {
                    echo esc_html($notaC);
                  } else {
                    echo esc_html(wp_trim_words($hab->post_excerpt ?: $hab->post_content, 22));
                  }
                  ?>
                  </h3>
                  <a class="room-cardv2__btn" href="<?php echo esc_url(get_permalink($hab->ID)); ?>"><?php esc_html_e('Ver más', 'mw25-child'); ?></a>
                </header>

                <?php $specs = $get_room_principal_specs($hab->ID); ?>
                <?php if ($specs): ?>
                  <div class="room-cardv2__specs">
                    <?php foreach ($specs as $spec): ?>
                      <span>
                        <i class="ph <?php echo esc_attr($spec['icon']); ?>"></i>
                        <?php echo esc_html($spec['name']); ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>

                <?php if ($card_amenities): ?>
                  <div class="room-cardv2__amenities room-cardv2__amenities--pipe">
                    <?php
                    $amenity_text = implode(' | ', array_map('trim', $card_amenities));
                    echo esc_html($amenity_text);
                    ?>
                  </div>
                <?php endif; ?>

                <?php if ($precio): ?>
                  <p class="room-cardv2__price">Desde $<?php echo number_format((float)$precio, 0, '.', ','); ?> MXN</p>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </section>
      <?php
    }

    foreach ($groups as $slug => $data) {
      if (isset($orden[$slug]) || empty($data['items'])) {
        continue;
      }
      ?>
      <section class="rooms-section">
        <h2 class="rooms-section__title"><?php echo esc_html($data['title']); ?></h2>
        <div class="rooms-grid">
          <?php foreach ($data['items'] as $hab):
            $img     = $get_room_image($hab->ID, 'large');
            $precio  = get_post_meta($hab->ID, 'hab_precio', true);
            $pers    = get_post_meta($hab->ID, 'hab_personas', true);
            $camas   = get_post_meta($hab->ID, 'hab_camas', true);
            $notaC   = get_post_meta($hab->ID, 'hab_nota_card', true);
            $abrevia = get_post_meta($hab->ID, 'hab_abreviatura', true);
            $specs   = $get_room_principal_specs($hab->ID);
            $card_amenities = $get_room_group_terms($hab->ID, ['amenidad-card','amenidad card','amenidades-card','card'], 4);
            ?>
            <article class="room-cardv2">
              <a class="room-cardv2__media" href="<?php echo esc_url(get_permalink($hab->ID)); ?>">
                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($hab->post_title); ?>">
                <?php if ($abrevia): ?><span class="room-cardv2__tag"><?php echo esc_html($abrevia); ?></span><?php endif; ?>
              </a>
              <div class="room-cardv2__body">
                <header class="room-cardv2__header">
                  <h3 class="room-cardv2__title">
                    <?php
                  if ($notaC) {
                    echo esc_html($notaC);
                  } else {
                    echo esc_html(wp_trim_words($hab->post_excerpt ?: $hab->post_content, 22));
                  }
                  ?>
                  </h3>
                  <a class="room-cardv2__btn" href="<?php echo esc_url(get_permalink($hab->ID)); ?>"><?php esc_html_e('Ver más', 'mw25-child'); ?></a>
                </header>
                
                <?php if ($specs): ?>
                  <div class="room-cardv2__specs">
                    <?php foreach ($specs as $spec): ?>
                      <span>
                        <i class="ph <?php echo esc_attr($spec['icon']); ?>"></i>
                        <?php echo esc_html($spec['name']); ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
                <?php if ($card_amenities): ?>
                  <div class="room-cardv2__amenities room-cardv2__amenities--pipe">
                    <?php
                    $amenity_text = implode(' | ', array_map('trim', $card_amenities));
                    echo esc_html($amenity_text);
                    ?>
                  </div>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </section>
      <?php
    }
    ?>
  </div>
</main>

<?php get_footer(); ?>
