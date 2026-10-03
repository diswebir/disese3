/** Browser audit for both modes. Set THEME_PREVIEW_URL to the root of preview/. */
const assert = require('node:assert/strict');
const puppeteer = require('puppeteer-core');
const chromium = require('@sparticuz/chromium').default;
const { AxePuppeteer } = require('@axe-core/puppeteer');
const base = process.env.THEME_PREVIEW_URL || 'http://127.0.0.1:8765/';
function contrast(first, second) {
  const luminance = str => {
    const rgb = (str.match(/[\d.]+/g) || []).slice(0, 3).map(Number);
    assert.equal(rgb.length, 3, `Not a solid RGB color: ${str}`);
    const linear = rgb.map(v => { v /= 255; return v <= .04045 ? v / 12.92 : ((v + .055) / 1.055) ** 2.4; });
    return linear[0] * .2126 + linear[1] * .7152 + linear[2] * .0722;
  };
  const a = luminance(first), b = luminance(second);
  return (Math.max(a, b) + .05) / (Math.min(a, b) + .05);
}
(async () => {
  const browser = await puppeteer.launch({ args: chromium.args, executablePath: await chromium.executablePath(), headless: true });
  try {
    const page = await browser.newPage();
    await page.setCacheEnabled(false);
    const client = await page.createCDPSession();
    await client.send('DOM.enable'); await client.send('CSS.enable');
    for (const mode of ['dark', 'light']) for (const route of ['', 'contact/']) {
      for (const width of [390, 1440]) {
        await page.setViewport({ width, height: 900 });
        const url = new URL((mode === 'light' ? 'light/' : '') + route, base).toString();
        assert.equal((await page.goto(url, { waitUntil: 'networkidle0' })).status(), 200);
        assert.equal(await page.$eval('html', element => element.dataset.theme), mode);
        const result = await new AxePuppeteer(page).analyze();
        assert.deepEqual(result.violations.map(v => `${v.id}: ${v.nodes.map(n => n.target).join(', ')}`), [], `axe ${mode} ${route || 'home'} ${width}px`);
        assert.equal(await page.evaluate(() => document.documentElement.scrollWidth > innerWidth), false, 'Horizontal overflow');
        console.log(`PASS: ${mode} ${route || 'home'} ${width}px — axe + layout`);
      }
      // No transitions while sampling Chrome's real computed hover and pressed colors.
      await page.addStyleTag({ content: '*,*::before,*::after{transition:none!important;animation:none!important}' });
      const selectors = route ? ['.contact-hero__buttons .btn--gold', '.contact-form__submit'] : ['.hero__buttons .btn--gold'];
      if (route) selectors.push('.contact-form__field input');
      if (mode === 'light' && route) selectors.push('.contact-channel');
      if (mode === 'light' && !route) {
        // Minimal archive/store fixture exercises CSS that mock home/contact cannot render.
        await page.evaluate(() => document.body.insertAdjacentHTML('beforeend', '<div class="inner-page"><nav class="category-chips"><a href="#" aria-current="page">فعال</a><a href="#">دسته‌بندی</a></nav><main class="shop-page"><div class="woocommerce"><a class="button" href="#">افزودن به سبد</a><ul class="products"><li class="product"><h2 class="woocommerce-loop-product__title">نور شهری</h2></li></ul></div></main></div>'));
        selectors.push('.category-chips a[aria-current=page]', '.woocommerce a.button');
      }
      for (const selector of selectors) {
        const documentNode = await client.send('DOM.getDocument');
        const { nodeId } = await client.send('DOM.querySelector', { nodeId: documentNode.root.nodeId, selector });
        assert.ok(nodeId, `Missing ${selector}`);
        for (const state of [[], ['hover'], ['hover', 'active'], ['focus', 'focus-visible']]) {
          await client.send('CSS.forcePseudoState', { nodeId, forcedPseudoClasses: state });
          await page.evaluate(() => new Promise(resolve => requestAnimationFrame(() => requestAnimationFrame(resolve))));
          const { foreground, background } = await page.$eval(selector, element => {
            const style = getComputedStyle(element);
            return { foreground: style.color, background: style.backgroundColor };
          });
          assert.ok(contrast(foreground, background) >= 4.5, `${mode} ${route} ${selector} ${state.join('+') || 'normal'}: ${foreground} on ${background}`);
        }
        await client.send('CSS.forcePseudoState', { nodeId, forcedPseudoClasses: [] });
      }
      console.log(`PASS: ${mode} ${route || 'home'} — normal, hover, active and focus control contrast`);
    }
  } finally { await browser.close(); }
})().catch(error => { console.error(error); process.exitCode = 1; });
