(function () {
  const onReady = () => {
    const toggle = document.querySelector('.site-nav__toggle');
    const nav = document.querySelector('.site-nav');

    if (!toggle || !nav) {
      return;
    }

    const menu = nav.querySelector('.site-nav__list');

    const closeMenu = () => {
      toggle.classList.remove('is-active');
      nav.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
    };

    const openMenu = () => {
      toggle.classList.add('is-active');
      nav.classList.add('is-open');
      toggle.setAttribute('aria-expanded', 'true');
    };

    toggle.addEventListener('click', () => {
      const isOpen = nav.classList.contains('is-open');
      if (isOpen) {
        closeMenu();
      } else {
        openMenu();
        toggle.focus();
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        if (nav.classList.contains('is-open')) {
          closeMenu();
          toggle.focus();
        }
      }
    });

    if (menu) {
      menu.addEventListener('click', (event) => {
        const target = event.target;
        if (target instanceof HTMLElement && target.tagName === 'A') {
          closeMenu();
        }
      });
    }
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', onReady);
  } else {
    onReady();
  }
})();
