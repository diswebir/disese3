/** Optional full-browser smoke test for the rendered preview.
 * Needs puppeteer-core and @sparticuz/chromium installed in test environment,
 * plus their system NSS libraries. Run with THEME_PREVIEW_URL pointing to preview server.
 */
const assert = require('node:assert/strict');
const puppeteer = require('puppeteer-core');
const chromium = require('@sparticuz/chromium').default;
const url = process.env.THEME_PREVIEW_URL || 'http://127.0.0.1:8765/';
(async () => {
  const browser = await puppeteer.launch({ args: chromium.args, executablePath: await chromium.executablePath(), headless: true });
  try {
    const page = await browser.newPage();
    await page.setCacheEnabled(false);
    const errors = [], external = [];
    page.on('pageerror', error => errors.push(error.message));
    page.on('request', request => {
      if (request.url().startsWith('http') && new URL(request.url()).origin !== new URL(url).origin) external.push(request.url());
    });
    for (const width of [320, 390, 768, 1024, 1440]) {
      await page.setViewport({ width, height: 850 });
      const response = await page.goto(url, { waitUntil: 'networkidle0' });
      assert.equal(response.status(), 200);
      const result = await page.evaluate(() => ({ overflow: document.documentElement.scrollWidth - innerWidth, sections: ['projects','about','products','services','contact'].every(id => !!document.getElementById(id)) }));
      assert.equal(result.overflow, 0, `Horizontal overflow at ${width}px`);
      assert.ok(result.sections, `Homepage section missing at ${width}px`);
      console.log(`PASS: responsive ${width}px`);
    }
    await page.setViewport({ width: 390, height: 844 });
    await page.goto(url, { waitUntil: 'networkidle0' });
    await page.click('.menu-toggle');
    assert.equal(await page.$eval('.menu-toggle', el => el.getAttribute('aria-expanded')), 'true');
    await page.keyboard.press('Escape');
    assert.equal(await page.$eval('.menu-toggle', el => el.getAttribute('aria-expanded')), 'false');
    await page.click('.header-search summary');
    const search = await page.$eval('.header-search__panel', el => { const rect = el.getBoundingClientRect(); return { left: rect.left, right: rect.right }; });
    assert.ok(search.left >= 0 && search.right <= 390, 'Search popup fits viewport');
    await page.click('[data-service="3"]');
    assert.equal(await page.$eval('#service-title', el => el.textContent), 'نورپردازی المان‌های شهری');
    await page.evaluate(async () => {
      document.querySelectorAll('img[loading="lazy"]').forEach(image => { image.loading = 'eager'; });
      await Promise.all([...document.images].map(image => image.decode().catch(() => null)));
    });
    assert.deepEqual(await page.evaluate(() => [...document.images].filter(image => !image.naturalWidth).map(image => image.src)), []);
    assert.deepEqual(errors, []);
    assert.deepEqual(external, []);
    console.log('PASS: mobile interactions, all images, no JS errors or external requests');
  } finally { await browser.close(); }
})().catch(error => { console.error(error); process.exitCode = 1; });
