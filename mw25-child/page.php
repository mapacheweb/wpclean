<?php
/**
 * Plantilla base para páginas internas
 */
if ( ! defined('ABSPATH') ) exit;

if ( ! function_exists('get_the_post_thumbnail_url') ) {
  require_once ABSPATH . WPINC . '/post-thumbnail-template.php';
}

get_header();

if ( have_posts() ) :
  while ( have_posts() ) : the_post();
    $post_id   = get_the_ID();
    $hero_img  = get_the_post_thumbnail_url($post_id, 'xxl');
    if ( ! $hero_img ) {
      $hero_img = get_theme_file_uri('assets/og-default.jpg');
    }
    $subtitle = get_post_meta($post_id, 'page_subtitle', true);
    if ( ! $subtitle && has_excerpt() ) {
      $subtitle = get_the_excerpt();
    }
    ?>

    <main id="site-main" class="page-wrap">
      <header class="page-hero" style="background-image:url('<?php echo esc_url($hero_img); ?>');">
        <div class="page-hero__overlay"></div>
        <div class="page-hero__content container">
          <h1 class="page-hero__title"><?php the_title(); ?></h1>
          <?php if ( $subtitle ) : ?>
            <p class="page-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
          <?php endif; ?>
        </div>
      </header>

      <section class="page-content container">
        <div class="page-content__inner">
          <?php the_content(); ?>
        </div>
      </section>
    </main>

    <?php
  endwhile;
endif;

get_footer();
