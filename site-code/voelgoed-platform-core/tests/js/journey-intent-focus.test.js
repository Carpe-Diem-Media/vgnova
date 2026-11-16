'use strict';
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');
const source = fs.readFileSync(path.join(__dirname, '../../includes/JourneyIntent/journey-intent-focus.js'), 'utf8');

function fixture() {
    let handler, queued, message = null;
    const calls = [];
    const form = { querySelector: selector => selector === '.message.success' ? message : null };
    const document = {
        addEventListener: (name, fn) => { if (name === 'bricks/form/success') handler = fn; },
        querySelector: selector => selector === 'form[data-element-id="jrn905"]' ? form : null,
    };
    vm.runInNewContext(source, { document, requestAnimationFrame: fn => { queued = fn; } });
    return {
        send: detail => handler({ detail }),
        insertMessage: () => { message = { setAttribute: (...args) => calls.push(args), focus: options => calls.push(['focus', options.preventScroll]) }; },
        frame: () => { if (queued) { const fn = queued; queued = null; fn(); } },
        calls,
    };
}
const good = { elementId: 'jrn905', res: { success: true, data: { type: 'success' } } };
{
    const f = fixture(); f.send(good); assert.equal(f.calls.length, 0); f.insertMessage(); f.frame();
    assert.deepEqual(f.calls, [['tabindex', '-1'], ['focus', true]]);
}
for (const detail of [
    { ...good, elementId: 'other' },
    { ...good, res: { success: false, data: { type: 'error' } } },
    { ...good, res: { success: true, data: { type: 'error' } } },
    {},
]) {
    const f = fixture(); f.insertMessage(); f.send(detail); f.frame(); assert.deepEqual(f.calls, []);
}
{ const f = fixture(); f.send(good); f.frame(); assert.deepEqual(f.calls, []); }
console.log('PASS focus follows only successful jrn905 response after message insertion');
