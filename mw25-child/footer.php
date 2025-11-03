<footer class="site-footer">
  <div class="footer-main">
    <div class="footer-main__inner container">
      <div class="footer-brand">
        <div class="footer-brand__logo">
          <?php
          if (has_custom_logo()) {
            the_custom_logo();
          } else {
            echo '<a class="footer-brand__title" href="' . esc_url(home_url('/')) . '">' . esc_html(get_bloginfo('name')) . '</a>';
          }
          ?>
        </div>
        <p class="footer-brand__copy">
          Ubicado cerca de los principales puntos emblemáticos de la ciudad, el Hotel Casablanca invita a vivir la esencia duranguense: descanso, buena comida y un servicio que ha hecho historia por generaciones.
        </p>
        <div class="footer-brand__actions">
          <a class="footer-btn footer-btn--ghost" href="https://maps.app.goo.gl/xF5ssAXy1AoKx249A" target="_blank" rel="noopener">
            <i class="ph ph-map-pin"></i> Ubicación en Google
          </a>
          <a class="footer-btn footer-btn--solid" href="/contacto">
            <i class="ph ph-calendar-check"></i> ¡Reserva aquí!
          </a>
        </div>
      </div>

      <div class="footer-links">
        <div class="footer-column">
          <h3 class="footer-column__title"><?php esc_html_e('Menú', 'mw25-child'); ?></h3>
          <?php
          if (has_nav_menu('primary')) {
            wp_nav_menu([
              'theme_location' => 'primary',
              'container'      => false,
              'menu_class'     => 'footer-menu',
              'fallback_cb'    => false,
            ]);
          } else {
            echo '<ul class="footer-menu"><li><a href="' . esc_url(admin_url('nav-menus.php')) . '">' . esc_html__('Configura el menú principal', 'mw25-child') . '</a></li></ul>';
          }
          ?>
        </div>

        <div class="footer-column">
          <h3 class="footer-column__title"><?php esc_html_e('Servicios', 'mw25-child'); ?></h3>
          <?php
          if (has_nav_menu('footer')) {
            wp_nav_menu([
              'theme_location' => 'footer',
              'container'      => false,
              'menu_class'     => 'footer-menu',
              'fallback_cb'    => false,
            ]);
          } else {
            echo '<ul class="footer-menu">';
            echo '<li>Wi-Fi gratuito</li>';
            echo '<li>Restaurante</li>';
            echo '<li>Estacionamiento</li>';
            echo '<li>Salón de eventos</li>';
            echo '<li>Servicio 24 hrs.</li>';
            echo '</ul>';
          }
          ?>
        </div>

        <div class="footer-column footer-column--contacto">
          <h3 class="footer-column__title"><?php esc_html_e('Contacto', 'mw25-child'); ?></h3>
          <ul class="footer-contact">
            <li><strong>Tel:</strong> <a href="tel:+526188113599">+52 618 811 3599</a></li>
            <li><strong>Email:</strong> <a href="mailto:info@hotelcasablanca.com">info@hotelcasablanca.com</a></li>
            <li>
              <strong><?php esc_html_e('Dirección:', 'mw25-child'); ?></strong>
              <span>Av. 20 de Noviembre 811 Pte,<br>Zona Centro, 34000 Durango, Dgo.</span>
            </li>
          </ul>
          <div class="footer-social">
            <a href="https://www.facebook.com/" target="_blank" rel="noopener" aria-label="Facebook">
              <i class="ph ph-facebook-logo"></i>
            </a>
            <a href="https://www.instagram.com/" target="_blank" rel="noopener" aria-label="Instagram">
              <i class="ph ph-instagram-logo"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="footer-bottom__inner container">
      <p>&copy; <?php echo esc_html(date_i18n('Y')); ?> <?php bloginfo('name'); ?> · Todos los derechos reservados · <a href="/aviso-de-privacidad">Política de Privacidad</a></p>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
