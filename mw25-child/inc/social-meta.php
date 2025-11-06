<?php
// /inc/social-meta.php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Metadatos sociales (Open Graph + Twitter Cards)
 * - Home: descripción personalizada
 * - Posts/Páginas: excerpt + imagen destacada
 * - Fallbacks: tagline + og-default.jpg
 */
function mm_social_meta_tags() {

  // HOME o portada
  if ( is_front_page() || is_home() ) {
    $title       = get_bloginfo('name') . ' | Hotel en Durango';
    $description = 'Hospédate en el corazón del Centro Histórico de Durango. Tradición, historia y sabor en un hotel emblemático con estilo clásico y atención moderna.';
    $url         = home_url('/');
    $image       = get_stylesheet_directory_uri() . '/assets/og-default.jpg';

  // Entradas o páginas individuales
  } elseif ( is_singular() ) {
    $title       = wp_get_document_title();
    $description = get_the_excerpt() ?: get_bloginfo('description');
    $url         = get_permalink();
    $image       = get_the_post_thumbnail_url(get_the_ID(), 'large');

  // Cualquier otra vista (archivos, categorías, etc.)
  } else {
    $title       = get_bloginfo('name') . ' | ' . get_bloginfo('description');
    $description = get_bloginfo('description');
    $url         = home_url('/');
    $image       = get_stylesheet_directory_uri() . '/assets/og-default.jpg';
  }

  // ✅ Fallback absoluto si no hay imagen destacada
  if ( empty( $image ) ) {
    $image = get_stylesheet_directory_uri() . '/assets/og-default.jpg';
  }

  // Sanitiza texto
  $description = esc_attr( wp_trim_words( $description, 30 ) );

  // Datos extra para imagen OG
  $secure    = preg_replace('#^http:#', 'https:', $image);
  $og_width  = 1200;
  $og_height = 630;
  $og_alt    = esc_attr( get_bloginfo('name') . ' — Imagen de previsualización' );

  echo "\n<!-- Social meta -->\n";
  ?>
  <meta property="og:type" content="<?php echo is_singular() ? 'article' : 'website'; ?>">
  <meta property="og:title" content="<?php echo esc_attr($title); ?>">
  <meta property="og:description" content="<?php echo $description; ?>">
  <meta property="og:url" content="<?php echo esc_url($url); ?>">

  <!-- Imagen OG explícita -->
  <meta property="og:image" content="<?php echo esc_url($image); ?>">
  <meta property="og:image:secure_url" content="<?php echo esc_url($secure); ?>">
  <meta property="og:image:width" content="<?php echo (int)$og_width; ?>">
  <meta property="og:image:height" content="<?php echo (int)$og_height; ?>">
  <meta property="og:image:alt" content="<?php echo $og_alt; ?>">

  <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">

  <!-- Twitter Cards -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
  <meta name="twitter:description" content="<?php echo $description; ?>">
  <meta name="twitter:url" content="<?php echo esc_url($url); ?>">
  <meta name="twitter:image" content="<?php echo esc_url($image); ?>">
  <?php
  echo "<!-- /Social meta -->\n";
}
// Prioridad 1: se imprime antes que cualquier otro plugin
add_action('wp_head', 'mm_social_meta_tags', 1);



/**
 * Añade prefijo OG al tag <html>
 * (algunos parsers lo requieren para reconocer las propiedades Open Graph)
 */
add_filter('language_attributes', function( $output ) {
  if ( strpos( $output, 'og:' ) === false ) {
    $output .= ' prefix="og: http://ogp.me/ns#"';
  }
  return $output;
});
