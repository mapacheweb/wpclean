<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#contenido-principal"><?php esc_html_e( 'Saltar al contenido principal', 'mw25-child' ); ?></a>
<header class="site-header" id="site-header">
    <div class="container site-header__inner">
        <div class="site-branding">
            <?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a class="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <?php bloginfo( 'name' ); ?>
                </a>
            <?php endif; ?>
        </div>
        <button class="site-nav__toggle" type="button" aria-controls="primary-menu" aria-expanded="false">
            <span class="site-nav__toggle-line" aria-hidden="true"></span>
            <span class="site-nav__toggle-line" aria-hidden="true"></span>
            <span class="site-nav__toggle-line" aria-hidden="true"></span>
            <span class="site-nav__label screen-reader-text"><?php esc_html_e( 'Menú', 'mw25-child' ); ?></span>
        </button>
        <nav class="site-nav" aria-label="<?php esc_attr_e( 'Menú principal', 'mw25-child' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'site-nav__list',
                    'container'      => false,
                    'fallback_cb'    => 'mm_primary_menu_fallback',
                    'depth'          => 2,
                )
            );
            ?>
        </nav>
    </div>
</header>
<main id="contenido-principal" class="site-main">
