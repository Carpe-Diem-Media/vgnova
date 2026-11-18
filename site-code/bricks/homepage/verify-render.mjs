import assert from 'node:assert/strict';
import { writeFile } from 'node:fs/promises';
import { createRequire } from 'node:module';
import { execFileSync } from 'node:child_process';
const require = createRequire(import.meta.url);
const globalRoot = execFileSync('npm', ['root', '-g'], { encoding: 'utf8' }).trim();
const puppeteer = require(`${globalRoot}/puppeteer-core`);
const paths = process.argv.slice(2);
assert(paths.length, 'Pass one or more HTML files generated from the saved Bricks render.');
const browser = await puppeteer.launch({ executablePath: '/usr/bin/google-chrome', headless: true, args: ['--no-sandbox', '--allow-file-access-from-files'] });
const results = [];
const searchResults = [];
try {
  for (const path of paths) {
    const page = await browser.newPage();
    for (const width of [320, 375, 991, 1280, 1920]) {
      await page.setViewport({ width, height: 900 });
      await page.goto(`file://${path}`, { waitUntil: 'networkidle0' });
      await page.emulateMediaFeatures([{ name: 'prefers-reduced-motion', value: 'reduce' }]);
      const state = await page.evaluate(() => {
        const root = document.querySelector('.c-homepage');
        const input = root.querySelector('input[type=search]');
        const button = root.querySelector('button[type=submit]');
        const grid = root.querySelector('.c-homepage__topic-grid');
        input.focus();
        const ring = getComputedStyle(input);
        return {
          h1: root.querySelectorAll('h1').length,
          topics: [...root.querySelectorAll('.c-homepage__topic-link')].map(a => ({ text: a.querySelector('h3').textContent, url: a.href })),
          actions: root.querySelectorAll('.c-homepage__actions a').length,
          columns: getComputedStyle(grid).gridTemplateColumns.split(' ').length,
          overflow: document.documentElement.scrollWidth > innerWidth,
          htmlClip: getComputedStyle(document.documentElement).overflowX,
          bodyClip: getComputedStyle(document.body).overflowX,
          labelled: root.querySelector(`label[for="${input.id}"]`)?.textContent,
          focusStyle: ring.outlineStyle,
          focusWidth: parseFloat(ring.outlineWidth),
          inputHeight: input.getBoundingClientRect().height,
          buttonHeight: button.getBoundingClientRect().height,
          searchVisible: input.getBoundingClientRect().bottom < innerHeight && button.getBoundingClientRect().bottom < innerHeight,
          transitionMs: parseFloat(ring.transitionDuration) * 1000,
          unresolved: /\{(?:acf_|post_|term_)/.test(root.innerHTML),
          formAction: input.form.action,
          postType: input.form.querySelector('[name=post_type]').value,
          feature: !!root.querySelector('.c-homepage__feature-title'),
          image: !!root.querySelector('.c-homepage__feature-image img'),
          featureColumns: root.querySelector('.c-homepage__feature-inner') ? getComputedStyle(root.querySelector('.c-homepage__feature-inner')).gridTemplateColumns.split(' ').length : 0,
        };
      });
      assert.equal(state.h1, 1);
      assert.equal(state.topics.length, 7);
      assert.equal(new Set(state.topics.map(t => t.url)).size, 7);
      assert.equal(state.topics[0].text, 'Nuwe Jy');
      assert.equal(state.actions, 4);
      assert.equal(state.columns, width <= 478 ? 1 : width <= 991 ? 2 : 4);
      assert.equal(state.overflow, false);
      assert.equal(state.htmlClip, 'clip');
      assert.equal(state.bodyClip, 'clip');
      assert.equal(state.labelled, 'Soek artikels');
      assert.equal(state.focusStyle, 'solid');
      assert(state.focusWidth >= 3);
      assert(Math.abs(state.inputHeight - state.buttonHeight) < 1);
      assert(state.searchVisible);
      assert(state.transitionMs <= 0.01);
      assert.equal(state.unresolved, false);
      assert.equal(state.postType, 'post');
      if (state.feature && !state.image) assert.equal(state.featureColumns, 1);
      await page.keyboard.press('Tab');
      assert(await page.evaluate(() => document.activeElement.matches('button[type=submit]')));
      results.push({ path, width, ...state });
    }
    if (path.endsWith('final-render.html')) {
      for (const term of ['Dink nuut', 'zzzxxyyhomepageunmatched']) {
        await page.goto(`file://${path}`, { waitUntil: 'networkidle0' });
        await page.type('.c-homepage input[name=s]', term);
        await Promise.all([
          page.waitForNavigation({ waitUntil: 'networkidle0' }),
          page.click('.c-homepage button[type=submit]'),
        ]);
        const url = new URL(page.url());
        assert.equal(url.searchParams.get('s'), term);
        assert.equal(url.searchParams.get('post_type'), 'post');
        const text = await page.$eval('main', element => element.innerText);
        if (term === 'Dink nuut') assert(text.includes('Dink nuut en beweeg vorentoe'));
        else assert(/no results|nothing found|geen resultate/i.test(text), text.slice(0, 500));
        searchResults.push({ term, url: page.url(), result: 'PASS', mainText: text.slice(0, 400) });
      }
    }
    await page.close();
  }
} finally { await browser.close(); }
await writeFile('/tmp/vgnova-home-interactions.json', JSON.stringify(results, null, 2));
await writeFile('/tmp/vgnova-home-search-checks.json', JSON.stringify(searchResults, null, 2));
console.log(`PASS ${results.length} saved-render viewport contexts and ${searchResults.length} native search submissions.`);
