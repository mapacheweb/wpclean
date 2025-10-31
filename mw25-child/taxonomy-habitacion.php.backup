<?php
// taxonomy-habitacion.php
if ( ! defined('ABSPATH') ) exit;
get_header();

$queried = get_queried_object();
$is_single_term = ( $queried && isset($queried->taxonomy) && $queried->taxonomy === 'habitacion' );
?>

<main class="hotel-wrap">
<?php if ( $is_single_term ) :

  // =============== FICHA =================
  $term      = $queried;
  $img_id    = get_term_meta($term->term_id, 'hab_imagen_id', true);
  $hero_img  = $img_id ? wp_get_attachment_image_url($img_id, 'xxl') : get_theme_file_uri('og-default.jpg');

  $cat_hab   = get_term_meta($term->term_id, 'hab_categoria', true);        // estandar | panoramica | plus
  $gal_ids   = get_term_meta($term->term_id, 'hab_galeria_ids', true);      // "12,45,99"
  $camas     = get_term_meta($term->term_id, 'hab_camas', true);
  $precio    = get_term_meta($term->term_id, 'hab_precio', true);
  $pers      = get_term_meta($term->term_id, 'hab_personas', true);
  $notaF     = get_term_meta($term->term_id, 'hab_nota_ficha', true);       // descripción larga
  $notaC     = get_term_meta($term->term_id, 'hab_nota_card', true);        // nota corta
  $amen_ids  = (array) get_term_meta($term->term_id, 'hab_amenidades', true);

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
      $grupos = (array) get_term_meta($aid, 'amenidad_grupos', true); // aquí vienen los IDs de grupo_amenidad

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

  <!-- ===== Hero superior ===== -->
  <header class="room-hero" style="background-image:url('<?php echo esc_url($hero_img); ?>')">
    <div class="room-hero__overlay"></div>
    <div class="room-hero__content container">
      <p class="room-hero__eyebrow">Habitaciones</p>
      <h1 class="room-hero__title"><?php echo esc_html($term->name); ?></h1>
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
      <img src="<?php echo esc_url($hero_img); ?>" alt="<?php echo esc_attr($term->name); ?>">
      <div class="room-gallery-strip__badge">
        <?php
          $label = $cat_hab === 'estandar'
            ? 'Estándar'
            : ( $cat_hab === 'panoramica' ? 'Panorámica' : ( $cat_hab === 'plus' ? 'Plus' : 'Habitación' ) );
          echo esc_html($label);
        ?>
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
        // si no hay galería, mete un placeholder
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
        // mostramos máximo 3 en esta franja
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
        <h2>Nuestra categoría más sencilla</h2>
        <?php if ($notaF): ?>
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

<?php else: ?>

  <?php
  // ============= LISTADO /habitaciones =============
  $terms = get_terms([
    'taxonomy'   => 'habitacion',
    'hide_empty' => false,
  ]);

  // agrupar por meta
  $groups = [
    'estandar'   => [],
    'panoramica' => [],
    'plus'       => [],
    'otras'      => [],
  ];

  if ( !is_wp_error($terms) && $terms ){
    foreach($terms as $t){
      $cat = get_term_meta($t->term_id, 'hab_categoria', true);
      if ( ! $cat ) $cat = 'otras';
      if ( ! isset($groups[$cat]) ) $groups[$cat] = [];
      $groups[$cat][] = $t;
    }
  }
  ?>

  <header class="hero-habitaciones">
    <div class="inner">
      <p class="eyebrow">Nuestras habitaciones</p>
      <h1>Elige la habitación ideal</h1>
      <p class="lead">Opciones para viajes de trabajo, familia o estancias largas.</p>
    </div>
  </header>

  <div class="container rooms-groups" style="padding:2.5rem 1.5rem 4rem;">
    <?php
    $orden = [
      'estandar'   => 'Habitaciones Estándar',
      'panoramica' => 'Habitaciones Panorámicas',
      'plus'       => 'Habitaciones Plus',
      'otras'      => 'Otras habitaciones',
    ];
    foreach($orden as $slug => $titulo){
      $list = isset($groups[$slug]) ? $groups[$slug] : [];
      if (!$list) continue;
      ?>
      <section class="rooms-section">
        <h2 class="rooms-section__title"><?php echo esc_html($titulo); ?></h2>
        <div class="rooms-grid">
          <?php foreach($list as $t):
            $img_id = get_term_meta($t->term_id, 'hab_imagen_id', true);
            $img    = $img_id ? wp_get_attachment_image_url($img_id,'large') : get_theme_file_uri('og-default.jpg');
            $precio = get_term_meta($t->term_id, 'hab_precio', true);
            $pers   = get_term_meta($t->term_id, 'hab_personas', true);
            $camas  = get_term_meta($t->term_id, 'hab_camas', true);
            $notaC  = get_term_meta($t->term_id, 'hab_nota_card', true);
            $link   = get_term_link($t);
            ?>
            <article class="room-card">
              <a href="<?php echo esc_url($link); ?>" class="room-card__media">
                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($t->name); ?>">
                <?php if ($notaC): ?><span class="room-card__badge"><?php echo esc_html($notaC); ?></span><?php endif; ?>
              </a>
              <div class="room-card__body">
                <h3 class="room-card__title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($t->name); ?></a></h3>
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

<?php endif; ?>
</main>

<?php get_footer(); ?>
