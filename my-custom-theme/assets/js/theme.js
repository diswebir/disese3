(() => {
  'use strict';
  const header = document.getElementById('site-header');
  const menu = document.querySelector('.menu-toggle');
  const nav = document.getElementById('primary-nav');
  if (header) {
    const update = () => header.classList.toggle('is-scrolled', window.scrollY > 50);
    update(); window.addEventListener('scroll', update, { passive: true });
  }
  if (menu && nav) {
    menu.addEventListener('click', () => {
      const open = menu.getAttribute('aria-expanded') !== 'true';
      menu.setAttribute('aria-expanded', String(open));
      menu.setAttribute('aria-label', open ? 'بستن منو' : 'باز کردن منو');
      nav.classList.toggle('is-open', open);
    });
    nav.addEventListener('click', event => {
      if (event.target.closest('a')) { nav.classList.remove('is-open'); menu.setAttribute('aria-expanded', 'false'); }
    });
    document.addEventListener('keydown', event => { if (event.key === 'Escape') { nav.classList.remove('is-open'); menu.setAttribute('aria-expanded', 'false'); } });
  }
  const serviceImage = document.getElementById('service-image');
  document.querySelectorAll('[data-service]').forEach(button => {
    button.addEventListener('click', () => {
      document.querySelectorAll('[data-service]').forEach(item => { item.classList.remove('is-active'); item.setAttribute('aria-pressed', 'false'); });
      button.classList.add('is-active'); button.setAttribute('aria-pressed', 'true');
      if (serviceImage) { serviceImage.src = button.dataset.image; serviceImage.alt = button.dataset.title; }
      const title = document.getElementById('service-title');
      const description = document.getElementById('service-description');
      if (title) title.textContent = button.dataset.title;
      if (description) description.textContent = button.dataset.description;
      const counter = document.querySelector('.services-visual__overlay span');
      if (counter) counter.textContent = `۰${Number(button.dataset.service) + 1} / ۰۵`;
    });
  });
})();
