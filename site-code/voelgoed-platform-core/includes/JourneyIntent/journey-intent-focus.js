'use strict';

// Bricks emits success before it inserts the response message into the form.
document.addEventListener('bricks/form/success', (event) => {
    const detail = event.detail;
    if (detail?.elementId !== 'jrn905' || detail.res?.success !== true || detail.res.data?.type !== 'success') {
        return;
    }

    requestAnimationFrame(() => {
        const form = document.querySelector('form[data-element-id="jrn905"]');
        const message = form?.querySelector('.message.success');
        if (!message) return;
        message.setAttribute('tabindex', '-1');
        message.focus({ preventScroll: true });
    });
});
