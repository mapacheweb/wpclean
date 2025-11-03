document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.primary-nav');

  const closeMenu = () => {
    document.body.classList.remove('nav-open');
    if (toggle) {
      toggle.setAttribute('aria-expanded', 'false');
    }
  };

  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const isOpen = toggle.getAttribute('aria-expanded') === 'true';
      if (isOpen) {
        closeMenu();
      } else {
        document.body.classList.add('nav-open');
        toggle.setAttribute('aria-expanded', 'true');
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        closeMenu();
      }
    });

    nav.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => {
        if (toggle.getAttribute('aria-expanded') === 'true') {
          closeMenu();
        }
      });
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth >= 960) {
        closeMenu();
      }
    });
  }

  const initPhotoSwipe = () => {
    const galleries = document.querySelectorAll('.pswp-gallery');
    if (!galleries.length) {
      return;
    }

    if (!window.PhotoSwipeLightbox || !window.PhotoSwipe) {
      return;
    }

    document.removeEventListener('mw:photoswipe-ready', initPhotoSwipe);

    galleries.forEach((galleryEl) => {
      if (galleryEl.dataset.pswpInit === 'true') {
        return;
      }

      const lightbox = new window.PhotoSwipeLightbox({
        gallery: galleryEl,
        children: 'a[data-pswp-src]',
        pswpModule: window.PhotoSwipe,
        paddingFn: (viewportSize) => (viewportSize.x < 700
          ? { top: 16, bottom: 16, left: 12, right: 12 }
          : { top: 32, bottom: 32, left: 24, right: 24 }),
      });

      lightbox.init();
      galleryEl.dataset.pswpInit = 'true';
    });
  };

  document.addEventListener('mw:photoswipe-ready', initPhotoSwipe);
  initPhotoSwipe();
});
