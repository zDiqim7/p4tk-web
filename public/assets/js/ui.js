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

const quantityPickers = document.querySelectorAll('[data-quantity-picker]');

const syncQuantityPicker = (picker, { emptyValue = false } = {}) => {
  const input = picker.querySelector('input[type="number"]');

  if (!(input instanceof HTMLInputElement)) {
    return;
  }

  const minimum = Number(input.min) || 1;
  const maximum = Number(input.max) || Number.MAX_SAFE_INTEGER;
  const parsedValue = Number(input.value);

  if (input.value === '' && emptyValue) {
    input.value = String(minimum);
  } else if (input.value !== '' && Number.isFinite(parsedValue)) {
    input.value = String(Math.min(maximum, Math.max(minimum, parsedValue)));
  }

  picker.querySelectorAll('[data-quantity-step]').forEach((button) => {
    if (!(button instanceof HTMLButtonElement)) {
      return;
    }

    const step = Number(button.dataset.quantityStep);
    const value = Number(input.value);
    button.disabled = Number.isFinite(value)
      && ((step < 0 && value <= minimum) || (step > 0 && value >= maximum));
  });
};

quantityPickers.forEach((picker) => {
  syncQuantityPicker(picker);

  const input = picker.querySelector('input[type="number"]');

  input?.addEventListener('input', () => syncQuantityPicker(picker));
  input?.addEventListener('change', () => syncQuantityPicker(picker, { emptyValue: true }));
  input?.addEventListener('blur', () => syncQuantityPicker(picker, { emptyValue: true }));
});

document.addEventListener('click', (event) => {
  const button = event.target instanceof Element
    ? event.target.closest('[data-quantity-step]')
    : null;
  const picker = button?.closest('[data-quantity-picker]');
  const input = picker?.querySelector('input[type="number"]');

  if (!(button instanceof HTMLButtonElement) || !(input instanceof HTMLInputElement)) {
    return;
  }

  const step = Number(button.dataset.quantityStep);
  const currentValue = Number(input.value) || Number(input.min) || 1;
  input.value = String(currentValue + step);
  input.dispatchEvent(new Event('input', { bubbles: true }));
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
