<?php
// single-habitacion.php - Ficha individual de habitación
if ( ! defined('ABSPATH') ) exit;
get_header();

while (have_posts()) : the_post();
  $post_id = get_the_ID();
  
  // Obtener metadatos
  $img_id    = get_the_post_thumbnail_id($post_id);
  $hero_img  = $img_id ? wp_get_attachment_image_url($img_id, 'xxl') : get_theme_file_uri('og-default.jpg');
  
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
        // traemos los nombres de los grupos para decidir
        $nombres = [];
        foreach($grupos as $gid){
          $gterm = get_term($gid, 'grupo_amenidad');
          if ( ! is_wp_error($gterm) && $gterm ){
            $nombres[] = trim($gterm->name);
          }
        }
        // decidir en base al nombre del grupo
        if ( array_intersect($nombres, ['Amenidades Principales', 'Principales']) ){
          $slot = 'principales';
        } elseif ( array_intersect($nombres, ['Amenidades Secundarias', 'Secundarias']) ){
          $slot = 'secundarias';
        } elseif ( array_intersect($nombres, ['Seguridad e Higiene', 'Seguridad e higiene', 'Salud y seguridad']) ){
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

  // procesar galería
  $gallery_images = [];
  if ($gal_ids){
    $ids = array_filter(array_map('intval', explode(',', $gal_ids)));
    foreach($ids as $gid){
      $url = wp_get_attachment_image_url($gid, 'large');
      if ($url){
        $gallery_images[] = $url;
      }
    }
  }
  ?>

<main class="hotel-wrap">
  <!-- ===== Hero superior ===== -->
  <header class="room-hero" style="background-image:url('<?php echo esc_url($hero_img); ?>')">
    <div class="room-hero__overlay"></div>
    <div class="room-hero__content container">
      <p class="room-hero__eyebrow">Habitaciones</p>
      <h1 class="room-hero__title"><?php the_title(); ?></h1>
      <?php if ($notaC): ?>
        <p class="room-hero__lede"><?php echo esc_html($notaC); ?></p>
      <?php else: ?>
        <p class="room-hero__lede">con vistas a diferentes puntos de la ciudad de Durango</p>
      <?php endif; ?>
    </div>
  </header>

  <!-- ===== Galería principal ===== -->
  <section class="room-gallery-strip container">
    <div class="room-gallery-strip__main">
      <img src="<?php echo esc_url($hero_img); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
      <div class="room-gallery-strip__badge">
        <?php echo esc_html($cat_name); ?>
      </div>
    </div>
    <div class="room-gallery-strip__thumbs">
      <?php
      $count = 0;
      if ($gallery_images){
        foreach($gallery_images as $gurl){
          $count++;
          if ($count <= 3){
            echo '<figure class="thumb"><img src="'.esc_url($gurl).'" alt=""></figure>';
          }
        }
        if (count($gallery_images) > 3){
          $resto = count($gallery_images) - 3;
          echo '<a class="thumb thumb--more" href="#galeria-completa">+'.intval($resto).' Fotos<br><small>Más</small></a>';
        }
      } else {
        echo '<figure class="thumb placeholder"><span>Sin galería</span></figure>';
      }
      ?>
    </div>
  </section>

  <!-- ===== Layout de contenido ===== -->
  <section class="room-layout container">
    <div class="room-layout__left">

      <!-- amenidades secundarias en 3 cards -->
      <?php if ($amen_secundarias): ?>
      <div class="room-features">
        <?php
        $max = 3;
        $shown = 0;
        foreach($amen_secundarias as $a){
          if ($shown >= $max) break;
          $shown++;
          ?>
          <div class="room-feature">
            <div class="room-feature__icon">
              <?php if ($a['icon']): ?>
                <i class="ph <?php echo esc_attr($a['icon']); ?>"></i>
              <?php else: ?>
                <i class="ph ph-circle"></i>
              <?php endif; ?>
            </div>
            <div>
              <p class="room-feature__title"><?php echo esc_html($a['name']); ?></p>
              <p class="room-feature__desc">a tu disposición en nuestra recepción</p>
            </div>
          </div>
          <?php
        }
        ?>
      </div>
      <?php endif; ?>

      <!-- descripción -->
      <article class="room-desc">
        <h2><?php echo esc_html($cat_name); ?></h2>
        
        <?php if ($descL): ?>
          <p><?php echo nl2br(esc_html($descL)); ?></p>
        <?php elseif (get_the_content()): ?>
          <div><?php the_content(); ?></div>
        <?php elseif ($notaF): ?>
          <p><?php echo nl2br(esc_html($notaF)); ?></p>
        <?php else: ?>
          <p>Con el confort y la calidez que distinguen al Hotel Casablanca, estas habitaciones han sido diseñadas para ofrecer descanso y comodidad a cada huésped.</p>
        <?php endif; ?>
      </article>

      <!-- Amenidades por grupos -->
      <div class="room-amenities-blocks" id="galeria-completa">

        <?php if ($amen_principales): ?>
          <section class="amen-block">
            <h3>Amenidades</h3>
            <ul>
              <?php foreach($amen_principales as $a): ?>
                <li>
                  <?php if ($a['icon']): ?><i class="ph <?php echo esc_attr($a['icon']); ?>"></i><?php endif; ?>
                  <?php echo esc_html($a['name']); ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </section>
        <?php endif; ?>

        <?php if ($amen_secundarias): ?>
          <section class="amen-block">
            <h3>Extras / secundarias</h3>
            <ul>
              <?php foreach($amen_secundarias as $a): ?>
                <li>
                  <?php if ($a['icon']): ?><i class="ph <?php echo esc_attr($a['icon']); ?>"></i><?php endif; ?>
                  <?php echo esc_html($a['name']); ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </section>
        <?php endif; ?>

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

    </div>

    <!-- Columna derecha -->
    <aside class="room-layout__right">
      <div class="booking-card">
        <p class="booking-card__label">Desde</p>
        <?php if ($precio): ?>
          <p class="booking-card__price">MX$<?php echo number_format((float)$precio, 2, '.', ','); ?></p>
        <?php else: ?>
          <p class="booking-card__price">Consulte</p>
        <?php endif; ?>
        <p class="booking-card__meta">1 noche, <?php echo $pers ? esc_html($pers) : '2 adultos'; ?></p>
        <div class="booking-card__actions">
          <a href="/contacto" class="btn btn-primary w-full">Reservar aquí</a>
          <a href="tel:+526180000000" class="btn btn-secondary w-full">Reservar por teléfono</a>
        </div>
        <a href="#" class="booking-card__link">Revisar términos y condiciones</a>
      </div>
    </aside>
  </section>
</main>

<?php 
endwhile;
get_footer(); 
?>