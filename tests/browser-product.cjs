/** Product-page mock smoke/a11y test for desktop/mobile and dark/light. Requires Chromium + axe. */
const assert = require('node:assert/strict');
const puppeteer = require('puppeteer-core');
const chromium = require('@sparticuz/chromium').default;
const { AxePuppeteer } = require('@axe-core/puppeteer');
const root = process.env.THEME_PREVIEW_URL || 'http://127.0.0.1:8765/';
(async () => {
  const browser = await puppeteer.launch({ args: chromium.args, executablePath: await chromium.executablePath(), headless: true });
  try {
    const page = await browser.newPage();
    await page.setCacheEnabled(false);
    const errors = [], external = [];
    page.on('pageerror', e => errors.push(e.message));
    page.on('request', req => { if (req.url().startsWith('http') && new URL(req.url()).origin !== new URL(root).origin) external.push(req.url()); });
    for (const mode of ['dark', 'light']) for (const width of [320, 390, 768, 1024, 1440]) {
      await page.setViewport({ width, height: 900 });
      const path = `${mode === 'light' ? 'light/' : ''}product/`;
      assert.equal((await page.goto(new URL(path, root).toString(), { waitUntil: 'networkidle0' })).status(), 200);
      const state = await page.evaluate(() => ({
        mode: document.documentElement.dataset.theme,
        overflow: document.documentElement.scrollWidth - innerWidth,
        title: !!document.querySelector('.es-product .product_title'),
        gallery: !!document.querySelector('.woocommerce-product-gallery img'),
        quote: !!document.querySelector('.es-product .es-inquiry a[href^="tel:"]'),
        tabs: !!document.querySelector('.es-product .woocommerce-tabs'),
        cta: !!document.querySelector('.es-product__consult a[href^="tel:"]'),
        related: document.querySelectorAll('.es-product__related-card').length === 3,
        imageLoaded: Array.from(document.querySelectorAll('.es-product img')).every(i => i.complete && i.naturalWidth),
        consultHeadingColor: getComputedStyle(document.querySelector('.es-product__consult h2')).color,
      }));
      assert.equal(state.mode, mode);
      assert.equal(state.overflow, 0, `Overflow at ${mode} ${width}px`);
      for (const key of ['title', 'gallery', 'quote', 'tabs', 'cta', 'related', 'imageLoaded']) assert.ok(state[key], `${key} at ${mode} ${width}px`);
      assert.equal(state.consultHeadingColor, 'rgb(255, 255, 255)', `Consult heading on dark panel at ${mode} ${width}px`);
      const audit = await new AxePuppeteer(page).analyze();
      assert.deepEqual(audit.violations.map(v => `${v.id} ${v.nodes.map(n => n.target).join(', ')}`), [], `Axe ${mode} ${width}px`);
      console.log(`PASS: single product ${mode} ${width}px — sections, images, layout and axe`);
    }
    for (const width of [390, 1440]) {
      await page.setViewport({ width, height: 900 });
      await page.goto(new URL('product-online/', root).toString(), { waitUntil: 'networkidle0' });
      assert.ok(await page.$('.es-product form.cart .single_add_to_cart_button'), 'Online purchase keeps the WooCommerce cart form');
      assert.equal(await page.$('.es-product .es-inquiry'), null, 'Online product must not display the inquiry box');
      assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0);
      const audit = await new AxePuppeteer(page).analyze();
      assert.deepEqual(audit.violations.map(v => v.id), [], `Online mock axe ${width}px`);
      console.log(`PASS: online-cart product ${width}px — cart form and axe`);
    }
    assert.deepEqual(errors, [], 'No JS errors');
    assert.deepEqual(external, [], 'No external assets');
  } finally { await browser.close(); }
})().catch(error => { console.error(error); process.exitCode = 1; });
