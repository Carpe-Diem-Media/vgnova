import assert from 'node:assert/strict';
import { createRequire } from 'node:module';
import { execFileSync } from 'node:child_process';
import { resolve, join } from 'node:path';
import { pathToFileURL } from 'node:url';

// Check HTML captured from bricks/render-elements, with the site's real CSS linked.
// This does not submit forms, buy tickets, or certify the checkout workflow.
const targets = process.argv.slice(2);
assert(targets.length, 'Pass one or more rendered HTML files.');
const globalRoot = execFileSync('npm', ['root', '-g'], { encoding: 'utf8' }).trim();
const require = createRequire(join(process.env.HALLMARK_PUPPETEER || globalRoot, 'package.json'));
const { default: puppeteer } = await import(pathToFileURL(require.resolve('puppeteer-core')).href);
const browser = await puppeteer.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome', headless: true });
const evidence = [];
try {
  for (const target of targets) {
    const page = await browser.newPage();
    await page.emulateMediaFeatures([{ name: 'prefers-reduced-motion', value: 'reduce' }]);
    for (const width of [320, 375, 991, 1280, 1920]) {
      await page.setViewport({ width, height: 800 });
      await page.goto(pathToFileURL(resolve(target)).href, { waitUntil: 'networkidle0' });
      const state = await page.evaluate(() => {
        const root = document.querySelector('.c-event-landing');
        const firstLink = root.querySelector('a');
        firstLink.focus();
        const focus = getComputedStyle(firstLink);
        const primary = firstLink.getBoundingClientRect();
        return {
          width: innerWidth,
          scrollWidth: document.documentElement.scrollWidth,
          h1Count: root.querySelectorAll('h1').length,
          unresolvedTags: /\{(?:acf_|vg_|cf_|post_)[^}]+\}/.test(root.innerText),
          brokenAnchors: [...root.querySelectorAll('a[href^="#"]')].map(a => a.getAttribute('href')).filter(h => !document.getElementById(h.slice(1))),
          focusStyle: focus.outlineStyle,
          focusWidth: parseFloat(focus.outlineWidth),
          activeIsPrimary: document.activeElement === firstLink,
          primaryBottom: primary.bottom,
          primaryRight: primary.right,
          invisibleRoot: getComputedStyle(root).visibility === 'hidden',
          motionDurations: root.getAnimations({ subtree: true }).map(animation => animation.effect.getTiming().duration),
          sectionCount: root.querySelectorAll(':scope > section').length,
          htmlOverflow: getComputedStyle(document.documentElement).overflowX,
          bodyOverflow: getComputedStyle(document.body).overflowX,
        };
      });
      assert(state.scrollWidth <= width, `${target}: horizontal overflow at ${width}`);
      assert.equal(state.h1Count, 1);
      assert.equal(state.unresolvedTags, false);
      assert.deepEqual(state.brokenAnchors, []);
      assert.equal(state.invisibleRoot, false);
      assert.equal(state.activeIsPrimary, true);
      assert.notEqual(state.focusStyle, 'none');
      assert(state.focusWidth >= 2);
      assert(state.motionDurations.every(duration => typeof duration === 'number' && duration <= 0.01), 'Reduced motion must remove sustained animation.');
      assert.equal(state.htmlOverflow, 'clip');
      assert.equal(state.bodyOverflow, 'clip');
      if (width === 1280) assert(state.primaryBottom <= 800, 'Primary CTA must fit in the desktop hero.');
      await page.keyboard.press('Tab');
      assert(await page.evaluate(() => document.activeElement.tagName === 'A'), 'Tab must reach the next native link.');
      evidence.push({ target, ...state });
    }
    await page.close();
  }
  console.log(JSON.stringify({ status: 'PASS', checks: evidence.length, evidence }, null, 2));
} finally {
  await browser.close();
}
