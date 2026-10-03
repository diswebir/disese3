/** Optional Chromium smoke test. Serve preview/ and set THEME_PREVIEW_URL if needed. */
const assert = require('node:assert/strict');
const puppeteer = require('puppeteer-core');
const chromium = require('@sparticuz/chromium').default;
const base = process.env.THEME_PREVIEW_URL || 'http://127.0.0.1:8765/';
(async () => {
  const browser = await puppeteer.launch({ args: chromium.args, executablePath: await chromium.executablePath(), headless: true });
  try {
    const page = await browser.newPage();
    await page.setCacheEnabled(false);
    const errors = [], external = [];
    page.on('pageerror', error => errors.push(error.message));
    page.on('request', request => {
      if (request.url().startsWith('http') && new URL(request.url()).origin !== new URL(base).origin) external.push(request.url());
    });
    for (const width of [320, 390, 768, 1024, 1440]) {
      await page.setViewport({ width, height: 850 });
      const response = await page.goto(new URL('contact/', base).toString(), { waitUntil: 'networkidle0' });
      assert.equal(response.status(), 200);
      const state = await page.evaluate(() => ({
        overflow: document.documentElement.scrollWidth - innerWidth,
        sections: ['contact-form','contact-channels-heading','contact-visit-heading','contact-faq-heading'].every(id => !!document.getElementById(id)),
        form: !!document.querySelector('form.contact-form'),
      }));
      assert.equal(state.overflow, 0, `Horizontal overflow at ${width}px`);
      assert.ok(state.sections && state.form);
      console.log(`PASS: contact layout at ${width}px`);
    }
    await page.setViewport({ width: 390, height: 844 });
    await page.goto(new URL('contact/', base).toString(), { waitUntil: 'networkidle0' });
    assert.equal(await page.$eval('.contact-form', form => form.checkValidity()), false, 'Empty form must be invalid');
    await page.type('[name="name"]', 'علی رضایی');
    await page.type('[name="phone"]', '09123456789');
    await page.type('[name="message"]', 'درخواست مشاوره درباره نورپردازی شهری اصفهان را دارم.');
    await page.select('[name="category"]', 'urban');
    await page.click('[name="consent"]');
    assert.equal(await page.$eval('.contact-form', form => form.checkValidity()), true, 'Completed form should be valid');
    await page.click('.contact-faq__items summary');
    assert.equal(await page.$eval('.contact-faq__items details', details => details.open), true);
    assert.deepEqual(errors, []);
    assert.deepEqual(external, []);
    console.log('PASS: contact form validation, FAQ, no JS errors or external assets');
    try {
      const { AxePuppeteer } = require('@axe-core/puppeteer');
      const a11y = await new AxePuppeteer(page).analyze();
      assert.deepEqual(a11y.violations.map(violation => violation.id), [], 'Accessibility audit should have no axe violations');
      console.log('PASS: automated axe accessibility audit');
    } catch (error) {
      if (error.code !== 'MODULE_NOT_FOUND') throw error;
    }
  } finally { await browser.close(); }
})().catch(error => { console.error(error); process.exitCode = 1; });
