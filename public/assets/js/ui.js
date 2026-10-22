document.documentElement.classList.add('js-enabled');

const root = document.documentElement;
const body = document.body;
const themeToggle = document.querySelector('.theme-toggle');
const menuToggle = document.querySelector('.nav-menu-toggle');
const navigationLinks = document.querySelector('.site-nav-links');

const setTheme = (theme) => {
  const isDark = theme === 'dark';
  const selectedTheme = isDark ? 'dark' : 'light';

  root.dataset.theme = selectedTheme;
  body.dataset.theme = selectedTheme;
  localStorage.setItem('kopisthetics-theme', selectedTheme);

  if (themeToggle instanceof HTMLButtonElement) {
    const nextTheme = isDark ? 'terang' : 'gelap';
    themeToggle.setAttribute('aria-label', `Ganti ke tema ${nextTheme}`);
    themeToggle.title = `Ganti ke tema ${nextTheme}`;
  }

  const themeColor = document.querySelector('meta[name="theme-color"]');
  if (themeColor instanceof HTMLMetaElement) {
    themeColor.content = isDark ? '#151a24' : '#D4E7FE';
  }
};

setTheme(root.dataset.theme === 'dark' ? 'dark' : 'light');

themeToggle?.addEventListener('click', () => {
  setTheme(root.dataset.theme === 'dark' ? 'light' : 'dark');
});

const closeNavigation = () => {
  if (!(menuToggle instanceof HTMLButtonElement) || !navigationLinks) {
    return;
  }

  menuToggle.setAttribute('aria-expanded', 'false');
  menuToggle.setAttribute('aria-label', 'Buka menu navigasi');
  navigationLinks.classList.remove('is-open');
};

menuToggle?.addEventListener('click', () => {
  if (!navigationLinks) {
    return;
  }

  const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
  menuToggle.setAttribute('aria-expanded', String(!isExpanded));
  menuToggle.setAttribute('aria-label', isExpanded ? 'Buka menu navigasi' : 'Tutup menu navigasi');
  navigationLinks.classList.toggle('is-open', !isExpanded);
});

navigationLinks?.querySelectorAll('a').forEach((link) => {
  link.addEventListener('click', closeNavigation);
});

document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape' && menuToggle?.getAttribute('aria-expanded') === 'true') {
    closeNavigation();
    menuToggle.focus();
  }
});

const animatedSections = document.querySelectorAll('.slide-in');

if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
  const sectionObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });

  animatedSections.forEach((section) => sectionObserver.observe(section));
} else {
  animatedSections.forEach((section) => section.classList.add('is-visible'));
}
