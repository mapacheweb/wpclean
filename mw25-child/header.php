<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#site-main">
  <?php esc_html_e('Saltar al contenido principal', 'mw25-child'); ?>
</a>

<header class="site-header" role="banner">
  <div class="site-header__inner container">
    <div class="site-branding">
      <?php if (has_custom_logo()) : ?>
        <?php the_custom_logo(); ?>
      <?php else : ?>
        <a class="site-title" href="<?php echo esc_url(home_url('/')); ?>">
          <?php bloginfo('name'); ?>
        </a>
        <?php if (get_bloginfo('description')) : ?>
          <p class="site-description"><?php bloginfo('description'); ?></p>
        <?php endif; ?>
      <?php endif; ?>
    </div>

    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-menu">
      <span class="nav-toggle__icon" aria-hidden="true"></span>
      <span class="nav-toggle__label"><?php esc_html_e('Menú', 'mw25-child'); ?></span>
    </button>

    <nav class="primary-nav" aria-label="<?php esc_attr_e('Menú principal', 'mw25-child'); ?>">
      <?php
      if (has_nav_menu('primary')) {
        wp_nav_menu([
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'menu menu--primary',
          'menu_id'        => 'primary-menu',
          'fallback_cb'    => false,
          'depth'          => 2,
        ]);
      } else {
        echo '<ul id="primary-menu" class="menu menu--primary">';
        echo '<li><a href="' . esc_url(admin_url('nav-menus.php')) . '">' . esc_html__('Configura el menú principal', 'mw25-child') . '</a></li>';
        echo '</ul>';
      }
      ?>
    </nav>
  </div>
</header>
