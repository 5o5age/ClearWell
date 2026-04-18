import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Game loader: any page with <div data-game="..."> gets its module lazily imported.
const gameModules = import.meta.glob('./games/*.js');

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-game]');
    if (!root) return;
    const slug = root.dataset.game;
    const loader = gameModules[`./games/${slug}.js`];
    if (!loader) return; // no module → leave fallback content
    loader().then((mod) => {
        if (typeof mod.init === 'function') mod.init(root);
    });
});
