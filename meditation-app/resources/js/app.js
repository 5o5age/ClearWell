import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const gameModules = import.meta.glob('./games/*.js');

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-game]');
    if (!root) return;
    const loader = gameModules[`./games/${root.dataset.game}.js`];
    if (!loader) return;
    loader().then((mod) => {
        if (typeof mod.init === 'function') mod.init(root);
    });
});
