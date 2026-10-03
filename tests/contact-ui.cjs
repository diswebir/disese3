/* Static contact-page DOM smoke test. Requires linkedom in NODE_PATH. */
const assert = require('node:assert/strict');
const fs = require('node:fs');
const { parseHTML } = require('linkedom');
const html = fs.readFileSync(process.env.RENDERED_CONTACT || '/tmp/es-contact.html', 'utf8');
const { document } = parseHTML(html);
assert.equal(document.documentElement.getAttribute('dir'), 'rtl');
assert.ok(document.querySelector('.contact-hero h1 em'));
assert.equal(document.querySelectorAll('.contact-channel').length, 3);
assert.ok(document.querySelector('.contact-map'));
assert.equal(document.querySelectorAll('.contact-faq__items details').length, 3);
const form = document.querySelector('.contact-form');
assert.ok(form && form.getAttribute('method') === 'post');
assert.equal(form.querySelector('[name="action"]').value, 'es_contact_submit');
assert.ok(form.querySelector('[name="es_contact_nonce"]'));
for (const name of ['name','phone','email','city','category','message','consent','website']) {
  assert.ok(form.querySelector(`[name="${name}"]`), `Missing contact field ${name}`);
}
for (const asset of document.querySelectorAll('img[src],script[src],link[rel="stylesheet"]')) {
  const src = asset.getAttribute('src') || asset.getAttribute('href');
  assert.ok(!/^https?:\/\//.test(src), `External asset: ${src}`);
  if (src.startsWith('/my-custom-theme/')) assert.ok(fs.existsSync(`.${src}`), `Missing asset: ${src}`);
}
console.log('PASS: dedicated contact page, form, channels, FAQ and local assets');
