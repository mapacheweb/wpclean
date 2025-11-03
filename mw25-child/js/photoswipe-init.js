document.addEventListener('DOMContentLoaded', () => {
  const lightbox = new PhotoSwipeLightbox({
    gallery: '.grid-galeria',       // Tu contenedor
    children: 'a',                  // Las imágenes están dentro de <a>
    pswpModule: PhotoSwipe          // Esto lo provee WordPress si usas wp_enqueue_script()
  });
  lightbox.init();
});
