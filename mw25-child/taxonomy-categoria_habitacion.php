<?php
/**
 * Plantilla para términos de categoria_habitacion.
 */
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

get_header();

$term = get_queried_object();
$descripcion = term_description( $term );

$query = new WP_Query(
  array(
    'post_type'      => 'habitacion',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
    'tax_query'      => array(
      array(
        'taxonomy' => 'categoria_habitacion',
        'field'    => 'term_id',
        'terms'    => $term ? $term->term_id : 0,
      ),
    ),
  )
);
?>

<section class="hero-habitaciones">
  <div class="inner">
    <p class="eyebrow">Categoría de habitación</p>
    <h1><?php echo esc_html( $term ? $term->name : __( 'Habitaciones', 'mw25-child' ) ); ?></h1>
    <?php if ( $descripcion ) : ?>
      <div class="lead"><?php echo wp_kses_post( wpautop( $descripcion ) ); ?></div>
    <?php endif; ?>
  </div>
</section>

<div class="container rooms-groups" style="padding:2.5rem 1.5rem 4rem;">
  <?php if ( $query->have_posts() ) : ?>
    <div class="rooms-grid">
      <?php
      while ( $query->have_posts() ) {
        $query->the_post();
        $post_id = get_the_ID();
        $img_id  = get_post_thumbnail_id( $post_id );
        $img     = $img_id ? wp_get_attachment_image_url( $img_id, 'large' ) : get_theme_file_uri( 'og-default.jpg' );
        $precio  = get_post_meta( $post_id, 'hab_precio', true );
        $pers    = get_post_meta( $post_id, 'hab_personas', true );
        $camas   = get_post_meta( $post_id, 'hab_camas', true );
        $notaC   = get_post_meta( $post_id, 'hab_nota_card', true );
        ?>
        <article class="room-card">
          <a href="<?php echo esc_url( get_permalink() ); ?>" class="room-card__media">
            <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
            <?php if ( $notaC ) : ?><span class="room-card__badge"><?php echo esc_html( $notaC ); ?></span><?php endif; ?>
          </a>
          <div class="room-card__body">
            <h2 class="room-card__title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a></h2>
            <div class="room-card__meta">
              <?php if ( $pers ) : ?><span class="chip"><?php echo esc_html( $pers ); ?></span><?php endif; ?>
              <?php if ( $camas ) : ?><span class="chip"><?php echo esc_html( $camas ); ?></span><?php endif; ?>
            </div>
            <?php if ( $precio ) : ?>
              <p class="room-card__price">Desde <strong>$<?php echo number_format( (float) $precio, 0, '.', ',' ); ?> MXN</strong></p>
            <?php endif; ?>
            <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-sm">Ver detalles</a>
          </div>
        </article>
        <?php
      }
      ?>
    </div>
  <?php else : ?>
    <p>No hay habitaciones dentro de esta categoría todavía.</p>
  <?php endif; ?>
</div>

<?php
wp_reset_postdata();
get_footer();
