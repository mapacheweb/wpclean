<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
</main>
<footer class="site-footer">
    <div class="container site-footer__inner">
        <nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Enlaces del pie de página', 'mw25-child' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'menu_class'     => 'site-footer__list',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'depth'          => 1,
                )
            );
            ?>
        </nav>
        <p class="site-footer__copy">© <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
