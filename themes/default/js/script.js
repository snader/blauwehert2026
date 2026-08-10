const runApp = () => {
  const darkToggle = document.querySelector('[data-dark-toggle]');
  const mobileToggle = document.querySelector('[data-mobile-toggle]');
  const mobileMenu = document.querySelector('[data-mobile-menu]');
  const header = document.querySelector('[data-header]');
  const animated = document.querySelectorAll('[data-animate]');

  const setTheme = (dark) => {
    document.documentElement.classList.toggle('dark', dark);
    localStorage.setItem('theme', dark ? 'dark' : 'light');
  };

  const savedTheme = localStorage.getItem('theme');
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
    setTheme(true);
  }

  if (darkToggle) {
    darkToggle.addEventListener('click', () => {
      setTheme(!document.documentElement.classList.contains('dark'));
    });
  }

  if (mobileToggle && mobileMenu) {
    mobileToggle.addEventListener('click', () => {
      const open = mobileMenu.classList.toggle('open');
      mobileToggle.setAttribute('aria-expanded', String(open));
    });

    mobileMenu.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => {
        mobileMenu.classList.remove('open');
        mobileToggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  if (header) {
    const updateHeader = () => {
      if (window.scrollY > 56) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    };
    updateHeader();
    window.addEventListener('scroll', updateHeader, { passive: true });
  }

  if (animated.length) {
    const observer = new IntersectionObserver((entries, observerApi) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const el = entry.target;
          el.style.animationDelay = `${el.dataset.delay || 0}ms`;
          el.classList.add('animate');
          observerApi.unobserve(el);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });
    animated.forEach((el) => observer.observe(el));
  }

  const form = document.getElementById('subscribe-form');
  if (form) {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      form.reset();
      alert('Thanks for subscribing!');
    });
  }
};

document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', runApp) : runApp();
