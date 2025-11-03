<?php
// /inc/setup-assets.php

// Evita acceso directo al archivo
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Encola estilos y scripts globales del child theme
 * - Fuentes (Google Fonts) con display=swap
 * - CSS del child con cache busting
 * - JS vanilla principal en el footer
 * - Phosphor Icons por CDN
 */
function mm_enqueue_assets() {

  // 1. Google Fonts (Open Sans + EB Garamond)
  //    - Sólo pesos necesarios para no inflar
  //    - display=swap evita FOIT (texto invisible)
  wp_enqueue_style(
    'mm-fonts',
    'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=EB+Garamond:wght@400;500;700&display=swap',
    array(),
    null
  );

  // 2. CSS del child theme
  //    - Depende de mm-fonts para que las fuentes ya estén declaradas
  //    - filemtime() para bust de caché en desarrollo/ajustes
  wp_enqueue_style(
    'mm-child',
    get_stylesheet_directory_uri() . '/style.css',
    array('mm-fonts'),
    filemtime( get_stylesheet_directory() . '/style.css' )
  );

  // 3. JS principal del front
  //    - Sin dependencias tipo jQuery
  //    - Carga en el footer para no bloquear el render
  wp_enqueue_script(
    'mm-main',
    get_stylesheet_directory_uri() . '/js/main.js',
    array(),
    filemtime( get_stylesheet_directory() . '/js/main.js' ),
    true
  );

  // 4. Phosphor Icons CDN
  //    - Para usar <i class="ph ph-phone"></i> o <ph-icon ...>
  //    - Cargamos en <head> (footer=false) para que los iconos
  //      ya estén listos en el primer paint
  wp_enqueue_script(
    'phosphor-icons',
    'https://unpkg.com/@phosphor-icons/web',
    array(),
    null,
    false
  );
}
add_action('wp_enqueue_scripts', 'mm_enqueue_assets');



/**
 * Preconnect a Google Fonts
 * - Le dice al navegador "vas a necesitar pedirle cosas a estos hosts"
 *   para reducir la latencia en la primera visita.
 * - Se imprime MUY arriba en <head> con prioridad 1.
 */
function mm_preconnect_fonts() {
  echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
  echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
}
add_action('wp_head', 'mm_preconnect_fonts', 1);

/**
 * Configuración general del tema hijo
 * - Desactiva plantillas de bloques (usaremos PHP tradicionales)
 * - Registra menús de navegación para header y footer
 */
add_action('after_setup_theme', function () {
  remove_theme_support('block-templates');
  remove_theme_support('block-template-parts');

  add_theme_support('post-thumbnails');
  add_theme_support('title-tag');

  register_nav_menus([
    'primary' => __('Menú principal', 'mw25-child'),
    'footer'  => __('Menú de pie de página', 'mw25-child'),
  ]);
}, 11);

/**
 * PhotoSwipe assets únicamente para las habitaciones
 */
add_action('wp_enqueue_scripts', function () {
  if (!is_singular('habitacion')) {
    return;
  }

  $base_uri  = get_stylesheet_directory_uri() . '/photoswipe';
  $base_path = get_stylesheet_directory() . '/photoswipe';

  $assets = [
    [
      'handle'    => 'photoswipe',
      'type'      => 'style',
      'local'     => $base_uri . '/photoswipe.css',
      'local_path'=> $base_path . '/photoswipe.css',
      'cdn'       => 'https://unpkg.com/photoswipe@5/dist/photoswipe.css',
      'deps'      => [],
    ],
    [
      'handle'    => 'photoswipe-lightbox',
      'type'      => 'style',
      'local'     => $base_uri . '/photoswipe-lightbox.css',
      'local_path'=> $base_path . '/photoswipe-lightbox.css',
      'cdn'       => 'https://unpkg.com/photoswipe@5/dist/photoswipe-lightbox.css',
      'deps'      => ['photoswipe'],
    ],
    [
      'handle'    => 'photoswipe',
      'type'      => 'script',
      'local'     => $base_uri . '/photoswipe.umd.min.js',
      'local_path'=> $base_path . '/photoswipe.umd.min.js',
      'cdn'       => 'https://unpkg.com/photoswipe@5/dist/photoswipe.umd.min.js',
      'deps'      => [],
      'in_footer' => true,
    ],
    [
      'handle'    => 'photoswipe-lightbox',
      'type'      => 'script',
      'local'     => $base_uri . '/photoswipe-lightbox.umd.min.js',
      'local_path'=> $base_path . '/photoswipe-lightbox.umd.min.js',
      'cdn'       => 'https://unpkg.com/photoswipe@5/dist/photoswipe-lightbox.umd.min.js',
      'deps'      => ['photoswipe'],
      'in_footer' => true,
    ],
  ];

  foreach ($assets as $asset) {
    $src     = $asset['local'];
    $version = null;

    if (!file_exists($asset['local_path'])) {
      $src = $asset['cdn'];
    } else {
      $version = filemtime($asset['local_path']);
    }

    if ($asset['type'] === 'style') {
      wp_enqueue_style(
        $asset['handle'],
        $src,
        $asset['deps'],
        $version
      );
    } else {
      wp_enqueue_script(
        $asset['handle'],
        $src,
        $asset['deps'],
        $version,
        !empty($asset['in_footer'])
      );
    }
  }

  wp_add_inline_script(
    'photoswipe-lightbox',
    'document.dispatchEvent(new CustomEvent("mw:photoswipe-ready"));'
  );
}, 20);
