<?php
/**
 * Plantilla de portada
 *
 * Muestra el contenido de la página asignada como Home en Ajustes > Lectura.
 */
if ( ! defined('ABSPATH') ) exit;

get_header();
?>

<main id="site-main" class="site-main container">
  <?php
  if (have_posts()) :
    while (have_posts()) :
      the_post();
      the_content();
    endwhile;
  else :
    ?>
    <section class="page-empty">
      <h1><?php esc_html_e('Contenido en construcción', 'mw25-child'); ?></h1>
      <p><?php esc_html_e('Todavía no hay bloques o contenido publicado para la portada.', 'mw25-child'); ?></p>
    </section>
    <?php
  endif;
  ?>
</main>

<?php
get_footer();
