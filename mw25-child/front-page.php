<?php
/**
 * Plantilla de portada
 *
 * Muestra el contenido de la página asignada como Home en Ajustes > Lectura.
 */
if ( ! defined('ABSPATH') ) exit;

get_header();

$habitaciones = get_posts([
  'post_type'      => 'habitacion',
  'post_status'    => 'publish',
  'posts_per_page' => 5,
  'orderby'        => 'menu_order',
  'order'          => 'ASC',
]);

$reviews = [
  [
    'title' => __('“Delicioso lugar para desayunar, comer o cenar.”', 'mw25-child'),
    'text'  => __('Me encanta su café, los cuernos de nuez y en esta temporada los chiles en nogada; todo muy casero.', 'mw25-child'),
    'name'  => 'Gloria Soria',
    'image' => '1-Gloria_Soria.png',
    'badge' => 'google.png',
    'source'=> 'Google',
  ],
  [
    'title' => __('“Excelente servicio, atención, ubicación y precio.”', 'mw25-child'),
    'text'  => __('La habitación muy confortable y con una vista a la ciudad espectacular. La comida en el restaurante de lo mejor, todo delicioso y a precios muy razonables. Volvería a hospedarme aquí, sin duda.', 'mw25-child'),
    'name'  => 'SERGIO, mx',
    'image' => 'booking.png',
    'badge' => 'booking.png',
    'source'=> 'Booking',
  ],
  [
    'title' => __('“Muy buen hotel para estar con tu familia de vacaciones.”', 'mw25-child'),
    'text'  => __('Además un excelente servicio: habitaciones muy amplias y limpias, además un personal muy amable y atento. Lo recomiendo.', 'mw25-child'),
    'name'  => 'Juan Manuel Lerma',
    'image' => '2-Juan_Manuel_Lerma.png',
    'badge' => 'google.png',
    'source'=> 'Google',
  ],
  [
    'title' => __('“Me encanta ese restaurant”', 'mw25-child'),
    'text'  => __('Las enchiladas son únicas, y los chiles en nogada no se diga; ¡súper recomendado!', 'mw25-child'),
    'name'  => 'Lupita B',
    'image' => '3-Lupita_B.png',
    'badge' => 'google.png',
    'source'=> 'Google',
  ],
  [
    'title' => __('“I booked the suite, very nice view”', 'mw25-child'),
    'text'  => __('Hotel is 1.5 blocks from Centro. Room service was same menu as restaurant and good, maybe 5% more expensive which is fine. Staff helped with tours and always found someone who spoke English.', 'mw25-child'),
    'name'  => 'Corey, ca',
    'image' => 'booking.png',
    'badge' => 'booking.png',
    'source'=> 'Booking',
  ],
  [
    'title' => __('“It was just what I needed.”', 'mw25-child'),
    'text'  => __('The room service was excellent, and the lobby felt like home — warm, welcoming, with live piano and accordion music that created a wonderful atmosphere.', 'mw25-child'),
    'name'  => 'Liz Mathew',
    'image' => '4-Liz_Mathew.png',
    'badge' => 'google.png',
    'source'=> 'Google',
  ],
  [
    'title' => __('“Las habitaciones están muy bien acondicionadas”', 'mw25-child'),
    'text'  => __('El servicio de limpieza muy bueno y el café de cortesía excelente; vale la pena. La ubicación queda cerca de lugares céntricos para comer y pasear.', 'mw25-child'),
    'name'  => 'Pepe Lerma',
    'image' => '5-Pepe_Lerma.png',
    'badge' => 'google.png',
    'source'=> 'Google',
  ],
  [
    'title' => __('“I will recommend to my family and friends!”', 'mw25-child'),
    'text'  => __('From start to end my experience was wonderful.', 'mw25-child'),
    'name'  => 'Robert Shea',
    'image' => '6-Robert_Shea.png',
    'badge' => 'google.png',
    'source'=> 'Google',
  ],
];
?>

<main id="site-main" class="front-page">
  <section class="home-hero" aria-labelledby="home-hero-title">
    <div class="home-hero__media" aria-hidden="true">
      <iframe
        src="https://www.youtube.com/embed/a8pbccXdjVw?autoplay=1&amp;mute=1&amp;loop=1&amp;playlist=a8pbccXdjVw&amp;controls=0&amp;showinfo=0&amp;modestbranding=1&amp;rel=0&amp;playsinline=1"
        title="<?php esc_attr_e('Video Hotel Casablanca Durango', 'mw25-child'); ?>"
        allow="autoplay; fullscreen; picture-in-picture"
        loading="lazy"
        allowfullscreen
      ></iframe>
    </div>
    <div class="home-hero__overlay"></div>
    <div class="home-hero__content container">
      <p class="home-hero__eyebrow"><?php esc_html_e('Hotel', 'mw25-child'); ?></p>
      <h1 id="home-hero-title" class="home-hero__title"><?php esc_html_e('Casablanca Durango', 'mw25-child'); ?></h1>
      <p class="home-hero__description">
        <?php esc_html_e('Tradición, Historia y Sabor en el Corazón del Centro Histórico.', 'mw25-child'); ?>
      </p>
      <div class="home-hero__cta">
        <a class="btn btn-gold home-hero__btn" href="<?php echo esc_url( get_post_type_archive_link('habitacion') ); ?>">
          <?php esc_html_e('Conocer habitaciones', 'mw25-child'); ?>
        </a>
        <div class="home-hero__highlights">
          <div class="home-hero__highlight">
            <i class="ph ph-building" aria-hidden="true"></i>
            <span><?php esc_html_e('Historia y Tradición', 'mw25-child'); ?></span>
          </div>
          <div class="home-hero__highlight">
            <i class="ph ph-bowl-steam" aria-hidden="true"></i>
            <span><?php esc_html_e('Cultura y Sabor', 'mw25-child'); ?></span>
          </div>
          <div class="home-hero__highlight">
            <i class="ph ph-calendar-check" aria-hidden="true"></i>
            <span><?php esc_html_e('Abierto los 365 días del año', 'mw25-child'); ?></span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="home-intro container">
    <div class="home-intro__text">
      <h2><?php esc_html_e('Vive la tradición del hotel Casablanca en Durango', 'mw25-child'); ?></h2>
      <p><?php esc_html_e('Descubre la esencia de Durango en un lugar emblemático desde 1945. El Hotel Casablanca no solo es un espacio para hospedarse, sino un ícono cultural que resguarda historia, sabor y tradición.', 'mw25-child'); ?></p>
      <p><?php esc_html_e('Aquí, cada comida es una experiencia: desayunos que energizan tu día, comidas y cenas que invitan a compartir, todo en un ambiente cálido y acompañado de música de piano en vivo.', 'mw25-child'); ?></p>
      <a class="btn btn-gold" href="https://hotelcasablancadurango.com.mx/nosotros/">
        Conócenos</a>
    </div>
    <div class="home-intro__media">
      <figure>
        <img src="<?php echo esc_url( get_theme_file_uri('assets/01.jpg') ); ?>" alt="<?php esc_attr_e('Vista interior del Hotel Casablanca Durango', 'mw25-child'); ?>">
      </figure>
    </div>
  </section>

  <section class="home-parallax">
    <div class="home-parallax__overlay">
      <h2><?php esc_html_e('Ubicado en el corazón del Centro Histórico de Durango,', 'mw25-child'); ?></h2>
      <p><?php echo __('el Hotel Casablanca es mucho más que un lugar de alojamiento;<br>es una ventana a la rica historia y cultura local.', 'mw25-child'); ?></p>
    </div>
  </section>

  <section class="home-rooms container" id="habitaciones">
    <div class="home-rooms__head">
      <div>
        <h2><?php esc_html_e('Habitaciones', 'mw25-child'); ?></h2>
        <p><?php esc_html_e('Nuestras habitaciones amplias y cómodas ofrecen todas las amenidades necesarias para una estancia placentera.', 'mw25-child'); ?></p>
      </div>
      <a class="btn btn-gold" href="<?php echo esc_url( get_post_type_archive_link('habitacion') ); ?>">
        <?php esc_html_e('Ver habitaciones', 'mw25-child'); ?>
      </a>
    </div>

    <?php if ($habitaciones): ?>
      <div class="home-rooms__grid">
        <?php foreach ($habitaciones as $habitacion):
          $img_id = function_exists('get_the_post_thumbnail_id') ? get_the_post_thumbnail_id($habitacion->ID) : 0;
          if (!$img_id) {
            $img_id = (int) get_post_meta($habitacion->ID, '_thumbnail_id', true);
          }

          $abrevia = get_post_meta($habitacion->ID, 'hab_abreviatura', true);
          $nota    = get_post_meta($habitacion->ID, 'hab_nota_card', true);
          $camas   = get_post_meta($habitacion->ID, 'hab_camas', true);
          $pers    = get_post_meta($habitacion->ID, 'hab_personas', true);
          $precio  = get_post_meta($habitacion->ID, 'hab_precio', true);
          ?>
          <article class="home-room-card">
            <a class="home-room-card__media" href="<?php echo esc_url( get_permalink($habitacion->ID) ); ?>">
              <?php if ($img_id): ?>
                <?php echo wp_get_attachment_image($img_id, 'large', false, [
                  'alt'     => $habitacion->post_title,
                  'loading' => 'lazy',
                ]); ?>
              <?php else: ?>
                <img src="<?php echo esc_url( get_theme_file_uri('assets/og-default.jpg') ); ?>" alt="<?php echo esc_attr($habitacion->post_title); ?>">
              <?php endif; ?>
              <?php if ($abrevia): ?>
                <span class="home-room-card__badge"><?php echo esc_html($abrevia); ?></span>
              <?php endif; ?>
            </a>
            <div class="home-room-card__body">
              <h3 class="home-room-card__title"><?php echo esc_html($habitacion->post_title); ?></h3>
              <?php if ($nota): ?>
                <p class="home-room-card__description"><?php echo esc_html($nota); ?></p>
              <?php endif; ?>
              <ul class="home-room-card__meta">
                <?php if ($camas): ?><li><i class="ph ph-bed"></i><?php echo esc_html($camas); ?></li><?php endif; ?>
                <?php if ($pers): ?><li><i class="ph ph-users"></i><?php echo esc_html($pers); ?></li><?php endif; ?>
                <?php if ($precio): ?><li><i class="ph ph-currency-dollar"></i><?php echo esc_html(sprintf(__('Desde $%s MXN', 'mw25-child'), number_format((float)$precio, 0, '.', ','))); ?></li><?php endif; ?>
              </ul>
              <a class="btn btn-ghost" href="<?php echo esc_url( get_permalink($habitacion->ID) ); ?>">
                <?php esc_html_e('Ver más detalles', 'mw25-child'); ?>
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="home-rooms__fallback"><?php esc_html_e('Muy pronto compartiremos nuestras habitaciones destacadas.', 'mw25-child'); ?></p>
    <?php endif; ?>
  </section>

  <section class="home-feature home-feature--restaurant container">
    <div class="home-feature__content">
      <span class="home-feature__eyebrow"><?php esc_html_e('Restaurante', 'mw25-child'); ?></span>
      <h2 class="home-feature__title"><?php esc_html_e('Un sabor que ha perdurado por generaciones en Durango', 'mw25-child'); ?></h2>
      <p><?php esc_html_e('Ubicado en el corazón del Centro Histórico de Durango, el restaurante del Hotel Casablanca es reconocido por generaciones como un verdadero ícono duranguense.', 'mw25-child'); ?></p>
      <p><?php esc_html_e('Desde su fundación, ha mantenido viva la tradición de cocinar con el alma, combinando los sabores auténticos de la cocina regional con toques de gastronomía internacional que conquistan todos los paladares.', 'mw25-child'); ?></p>
      <a class="btn btn-gold" href="<?php echo esc_url( home_url('/restaurante') ); ?>">
        <?php esc_html_e('Conoce el restaurante', 'mw25-child'); ?>
      </a>
    </div>
    <div class="home-feature__media">
      <img src="<?php echo esc_url( get_theme_file_uri('assets/home_Restaurante.jpg') ); ?>" alt="<?php esc_attr_e('Restaurante del Hotel Casablanca Durango', 'mw25-child'); ?>">
    </div>
  </section>

  <section class="home-feature home-feature--salon container">
    <div class="home-feature__content">
      <span class="home-feature__eyebrow"><?php esc_html_e('Salón Guadiana', 'mw25-child'); ?></span>
      <h2 class="home-feature__title"><?php esc_html_e('El espacio ideal para tus eventos en Durango', 'mw25-child'); ?></h2>
      <p><?php esc_html_e('En el Hotel Casablanca, ponemos a tu disposición el Salón Guadiana, un espacio versátil y elegante diseñado para adaptarse a todo tipo de reuniones, conferencias, ruedas de prensa, cursos, graduaciones y celebraciones sociales o empresariales.', 'mw25-child'); ?></p>
      <a class="btn btn-gold" href="<?php echo esc_url( home_url('/salon-guadiana') ); ?>">
        <?php esc_html_e('Conoce el salón', 'mw25-child'); ?>
      </a>
    </div>
    <div class="home-feature__media">
      <img src="<?php echo esc_url( get_theme_file_uri('assets/home_salon.jpg') ); ?>" alt="<?php esc_attr_e('Salón Guadiana preparado para eventos', 'mw25-child'); ?>">
    </div>
  </section>

<section class="cintillo">
    ABIERTO LOS 365 DÍAS DEL AÑO  •  SERVICIO  •  HISTORIA  •  TRADICIÓN  •  CULTURA  •  SABOR  •  CENTRO HISTÓRICO  •   ABIERTO LOS 365 DÍAS DEL AÑO  •  SERVICIO  •  HISTORIA  •  TRADICIÓN  •  CULTURA  •  SABOR  •  CENTRO HISTÓRICO  • ABIERTO LOS 365 DÍAS DEL AÑO  •  SERVICIO  •  HISTORIA  •  TRADICIÓN  •  CULTURA  •  SABOR  •  CENTRO HISTÓRICO  •   ABIERTO LOS 365 DÍAS DEL AÑO  •  SERVICIO  •  HISTORIA  •  TRADICIÓN  •  CULTURA  •  SABOR  •  CENTRO HISTÓRICO  • 
</section>

  <?php if ($reviews): ?>
    <?php $reviews_twice = array_merge($reviews, $reviews); ?>
    <section class="home-reviews" aria-labelledby="home-reviews-title">
      <div class="container">
        <h2 id="home-reviews-title" class="home-reviews__title"><?php esc_html_e('Reseñas', 'mw25-child'); ?></h2>
      </div>
      <div class="home-reviews__scroller">
      <div class="home-reviews__track">
        <?php foreach ($reviews_twice as $index => $review):
          $image_relative = 'assets/' . $review['image'];
          $image_path     = trailingslashit(get_theme_file_path()) . $image_relative;
          $has_image      = file_exists($image_path);
          $image_url      = $has_image ? get_theme_file_uri($image_relative) : get_theme_file_uri('assets/og-default.jpg');
          $badge_relative = !empty($review['badge']) ? 'assets/' . $review['badge'] : '';
          $badge_path     = $badge_relative ? trailingslashit(get_theme_file_path()) . $badge_relative : '';
          $badge_url      = ($badge_relative && file_exists($badge_path)) ? get_theme_file_uri($badge_relative) : '';
          $is_duplicate   = $index >= count($reviews);
          ?>
          <article class="home-review-card" <?php echo $is_duplicate ? 'aria-hidden="true"' : ''; ?>>
            <div class="home-review-card__stars" aria-hidden="true">
              <i class="ph-fill ph-star"></i>
              <i class="ph-fill ph-star"></i>
              <i class="ph-fill ph-star"></i>
              <i class="ph-fill ph-star"></i>
              <i class="ph-fill ph-star"></i>
            </div>
            <?php if ($badge_url || !empty($review['source'])): ?>
              <div class="home-review-card__badge" aria-hidden="true">
                <?php if ($badge_url): ?>
                  <img src="<?php echo esc_url($badge_url); ?>" alt="">
                <?php endif; ?>
                <?php if (!empty($review['source'])): ?>
                  <span><?php echo esc_html($review['source']); ?></span>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <h3 class="home-review-card__title"><?php echo esc_html($review['title']); ?></h3>
            <p class="home-review-card__text"><?php echo esc_html($review['text']); ?></p>
            <div class="home-review-card__author">
              <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($review['name']); ?>">
              <span><?php echo esc_html($review['name']); ?></span>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <section class="home-whatsapp">
    <div class="home-whatsapp__inner container">
      <div class="home-whatsapp__media" aria-hidden="true">
        <img src="<?php echo esc_url( get_theme_file_uri('assets/phone.png') ); ?>" alt="">
      </div>
      <div class="home-whatsapp__content">
        <h2><?php esc_html_e('Hay algo que podamos hacer por ti?', 'mw25-child'); ?></h2>
        <p><?php esc_html_e('Estaremos encantados de ayudarte y brindarte la información que necesites.', 'mw25-child'); ?></p>
        <a class="btn btn-whatsapp" href="https://wa.me/526181369761?text=Hola%2C%20me%20interesa%20reservar%20Habitaci%C3%B3n">
          <i class="ph-bold ph-whatsapp-logo"></i>
          <?php esc_html_e('Contáctanos', 'mw25-child'); ?>
        </a>
      </div>
    </div>
  </section>
</main>

<?php
wp_reset_postdata();
get_footer();
