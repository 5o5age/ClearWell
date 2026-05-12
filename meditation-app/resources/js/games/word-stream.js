// Word Stream — type drifting words to clear them.

const WORDS = [
    'miers','klusums','plūsma','vilnis','tīrs','atpūta','maigs','prāts','elpa',
    'upe','akmens','mākonis','gaisma','debesis','sūnas','lapa','priede','rīts',
    'migla','dziļš','lēns','ezers','mežs','sapnis','peldēt','klusa','silti','mājas',
    'ziedēt','ēna','vilnītis','sirds','rasa','dvēsele','klausies','dzirdi','redzi',
    'rosa','ziedi','siltums','spožums','sapņot','elpot','plūst','kavēties','liegs','vakars',
];

const SPEED_PX_PER_SEC = 55;
const SPEED_JITTER     = 25;
const SPAWN_MIN_MS     = 1600;
const SPAWN_MAX_MS     = 2800;
const DRIFT_AMPLITUDE  = 18;
const DRIFT_FREQ       = 0.0005;

export function init(root) {
    root.innerHTML = '';
    root.classList.remove('items-center', 'justify-center');
    root.classList.add('relative');

    if (!document.getElementById('word-stream-styles')) {
        const style = document.createElement('style');
        style.id = 'word-stream-styles';
        style.textContent = STYLES;
        document.head.appendChild(style);
    }

    const scene = document.createElement('div');
    scene.className = 'ws-scene';
    root.appendChild(scene);

    const hud = document.createElement('div');
    hud.className = 'ws-hud';
    hud.innerHTML = `
        <div class="ws-stat">
            <span class="ws-stat-label">Notīrīti</span>
            <span class="ws-stat-value" data-score>0</span>
        </div>
        <div class="ws-stat">
            <span class="ws-stat-label">Kombo</span>
            <span class="ws-stat-value" data-combo>0</span>
        </div>
    `;
    root.appendChild(hud);

    const buffer = document.createElement('div');
    buffer.className = 'ws-buffer';
    buffer.textContent = 'Sāc rakstīt…';
    root.appendChild(buffer);

    const state = {
        words: [],
        active: null,
        score: 0,
        combo: 0,
        bestCombo: 0,
        lastSpawn: 0,
        nextSpawnDelay: rand(SPAWN_MIN_MS, SPAWN_MAX_MS),
        startTime: performance.now(),
        running: true,
    };

    const scoreEl = hud.querySelector('[data-score]');
    const comboEl = hud.querySelector('[data-combo]');

    let lastFrame = performance.now();
    function frame(now) {
        if (!state.running) return;
        const dt = (now - lastFrame) / 1000;
        lastFrame = now;

        if (now - state.startTime - state.lastSpawn > state.nextSpawnDelay) {
            spawnWord(scene, state);
            state.lastSpawn = now - state.startTime;
            state.nextSpawnDelay = rand(SPAWN_MIN_MS, SPAWN_MAX_MS);
        }

        if (state.words.length < 2 && now - state.startTime > 400) {
            spawnWord(scene, state);
        }

        for (let i = state.words.length - 1; i >= 0; i--) {
            const w = state.words[i];
            if (w.done) continue;
            w.x -= w.speed * dt;
            const bob = Math.sin(now * DRIFT_FREQ + w.phase) * DRIFT_AMPLITUDE;
            w.el.style.transform = `translate3d(${w.x}px, ${w.baseY + bob}px, 0)`;

            if (w.x < 80) {
                w.el.style.opacity = Math.max(0, w.x / 80).toFixed(2);
            }

            if (w.x + w.el.offsetWidth < -40) {
                removeWord(state, w);
                breakCombo(state, comboEl);
            }
        }

        requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);

    function onKey(e) {
        if (e.metaKey || e.ctrlKey || e.altKey) return;
        if (e.key === 'Backspace') { resetActive(state); buffer.textContent = 'Sāc rakstīt…'; return; }
        if (e.key.length !== 1) return;
        const ch = e.key.toLowerCase();
        if (!/[a-zāčēģīķļņōŗšūž]/.test(ch)) return;

        e.preventDefault();

        if (!state.active) {
            const candidate = pickWord(state, ch);
            if (!candidate) { flashBuffer(buffer, false); return; }
            state.active = candidate;
            candidate.el.classList.add('ws-word-active');
        }

        const w = state.active;
        const expected = w.text[w.typed];
        if (ch === expected) {
            w.typed += 1;
            renderWord(w);
            buffer.textContent = w.text.slice(0, w.typed) + '▌';
            buffer.classList.remove('ws-buffer-err');
            if (w.typed >= w.text.length) {
                completeWord(scene, state, w, scoreEl, comboEl);
                buffer.textContent = 'Sāc rakstīt…';
            }
        } else {
            w.typed = 0;
            renderWord(w);
            w.el.classList.remove('ws-word-active');
            w.el.classList.add('ws-word-shake');
            setTimeout(() => w.el.classList.remove('ws-word-shake'), 350);
            state.active = null;
            breakCombo(state, comboEl);
            flashBuffer(buffer, false);
            buffer.textContent = 'Sāc rakstīt…';
        }
    }
    window.addEventListener('keydown', onKey);

    const observer = new MutationObserver(() => {
        if (!document.body.contains(root)) {
            state.running = false;
            window.removeEventListener('keydown', onKey);
            observer.disconnect();
        }
    });
    observer.observe(document.body, { childList: true, subtree: true });
}

function rand(min, max) { return min + Math.random() * (max - min); }

function spawnWord(scene, state) {
    const text = WORDS[Math.floor(Math.random() * WORDS.length)];
    const el = document.createElement('div');
    el.className = 'ws-word';

    const w = {
        el,
        text,
        typed: 0,
        x: scene.clientWidth + 20,
        baseY: rand(20, Math.max(40, scene.clientHeight - 60)),
        y: 0,
        speed: SPEED_PX_PER_SEC + rand(-SPEED_JITTER, SPEED_JITTER),
        phase: rand(0, Math.PI * 2),
        done: false,
    };
    renderWord(w);
    scene.appendChild(el);
    state.words.push(w);
}

function renderWord(w) {
    w.el.innerHTML = '';
    for (let i = 0; i < w.text.length; i++) {
        const span = document.createElement('span');
        span.textContent = w.text[i];
        if (i < w.typed) span.className = 'ws-char-hit';
        w.el.appendChild(span);
    }
}

function pickWord(state, ch) {
    let best = null;
    for (const w of state.words) {
        if (w.done) continue;
        if (w.text[0] !== ch) continue;
        if (!best || w.x < best.x) best = w;
    }
    return best;
}

function resetActive(state) {
    if (!state.active) return;
    state.active.typed = 0;
    renderWord(state.active);
    state.active.el.classList.remove('ws-word-active');
    state.active = null;
}

function completeWord(scene, state, w, scoreEl, comboEl) {
    w.done = true;
    state.active = null;
    state.score += 1;
    state.combo += 1;
    if (state.combo > state.bestCombo) state.bestCombo = state.combo;
    scoreEl.textContent = state.score;
    comboEl.textContent = state.combo;
    bumpEl(scoreEl);
    bumpEl(comboEl);

    const rect = w.el.getBoundingClientRect();
    const sceneRect = scene.getBoundingClientRect();
    const cx = rect.left - sceneRect.left + rect.width / 2;
    const cy = rect.top - sceneRect.top + rect.height / 2;
    spawnBurst(scene, cx, cy);

    w.el.classList.add('ws-word-clear');
    setTimeout(() => {
        removeWord(state, w);
    }, 420);
}

function removeWord(state, w) {
    const i = state.words.indexOf(w);
    if (i >= 0) state.words.splice(i, 1);
    if (w.el.parentNode) w.el.parentNode.removeChild(w.el);
}

function spawnBurst(scene, x, y) {
    const count = 10;
    for (let i = 0; i < count; i++) {
        const p = document.createElement('span');
        p.className = 'ws-particle';
        const angle = (Math.PI * 2 * i) / count + rand(-0.2, 0.2);
        const dist  = rand(40, 90);
        const dx = Math.cos(angle) * dist;
        const dy = Math.sin(angle) * dist;
        p.style.left = x + 'px';
        p.style.top  = y + 'px';
        p.style.setProperty('--dx', dx + 'px');
        p.style.setProperty('--dy', dy + 'px');
        scene.appendChild(p);
        setTimeout(() => p.remove(), 700);
    }
}

function breakCombo(state, comboEl) {
    if (state.combo === 0) return;
    state.combo = 0;
    comboEl.textContent = '0';
}

function flashBuffer(buffer, ok) {
    buffer.classList.remove('ws-buffer-err');
    if (!ok) {
        buffer.classList.add('ws-buffer-err');
        setTimeout(() => buffer.classList.remove('ws-buffer-err'), 220);
    }
}

function bumpEl(el) {
    el.classList.remove('ws-bump');
    void el.offsetWidth;
    el.classList.add('ws-bump');
}

const STYLES = `
.ws-scene {
    position: absolute; inset: 0;
    overflow: hidden;
    border-radius: inherit;
}
.ws-word {
    position: absolute;
    top: 0; left: 0;
    padding: 8px 16px;
    font-family: 'DM Sans', sans-serif;
    font-size: 1.25rem;
    font-weight: 500;
    letter-spacing: 0.02em;
    color: oklch(var(--bc) / 0.55);
    background: oklch(var(--b1) / 0.6);
    border: 1px solid oklch(var(--bc) / 0.08);
    border-radius: 9999px;
    backdrop-filter: blur(6px);
    will-change: transform, opacity;
    transition: color .2s, border-color .2s, background .2s, transform .25s;
    user-select: none;
    white-space: nowrap;
}
.ws-word-active {
    border-color: oklch(var(--p) / 0.5);
    background: oklch(var(--p) / 0.08);
    box-shadow: 0 0 0 4px oklch(var(--p) / 0.08);
}
.ws-word .ws-char-hit {
    color: oklch(var(--p));
    text-shadow: 0 0 12px oklch(var(--p) / 0.5);
}
.ws-word-clear {
    animation: ws-pop .42s ease-out forwards;
}
@keyframes ws-pop {
    0%   { transform: translate3d(var(--tx, 0), var(--ty, 0), 0) scale(1); opacity: 1; }
    40%  { transform: scale(1.25); opacity: 1; }
    100% { transform: scale(0.6); opacity: 0; }
}
.ws-word-shake { animation: ws-shake .35s ease-in-out; }
@keyframes ws-shake {
    0%,100% { margin-left: 0; }
    20% { margin-left: -4px; } 40% { margin-left: 4px; }
    60% { margin-left: -3px; } 80% { margin-left: 2px; }
}

.ws-particle {
    position: absolute;
    width: 6px; height: 6px;
    border-radius: 9999px;
    background: oklch(var(--p));
    box-shadow: 0 0 10px oklch(var(--p) / 0.8);
    pointer-events: none;
    animation: ws-particle .7s ease-out forwards;
    transform: translate(-50%, -50%);
}
@keyframes ws-particle {
    0%   { opacity: 1; transform: translate(-50%, -50%); }
    100% { opacity: 0; transform: translate(calc(-50% + var(--dx)), calc(-50% + var(--dy))) scale(0.2); }
}

.ws-hud {
    position: absolute; top: 16px; left: 20px;
    display: flex; gap: 24px;
    pointer-events: none;
    z-index: 2;
}
.ws-stat { display: flex; flex-direction: column; gap: 2px; }
.ws-stat-label {
    font-size: 0.65rem;
    font-weight: 600;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: oklch(var(--bc) / 0.4);
}
.ws-stat-value {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 500;
    color: oklch(var(--bc) / 0.85);
    font-variant-numeric: tabular-nums;
    display: inline-block;
}
.ws-bump { animation: ws-bump .28s ease-out; }
@keyframes ws-bump {
    0% { transform: scale(1); }
    40% { transform: scale(1.25); color: oklch(var(--p)); }
    100% { transform: scale(1); }
}

.ws-buffer {
    position: absolute; bottom: 16px; left: 50%;
    transform: translateX(-50%);
    font-family: 'DM Sans', monospace;
    font-size: 0.85rem;
    letter-spacing: 0.05em;
    color: oklch(var(--bc) / 0.5);
    padding: 8px 18px;
    background: oklch(var(--b1) / 0.7);
    border: 1px solid oklch(var(--bc) / 0.1);
    border-radius: 9999px;
    backdrop-filter: blur(6px);
    transition: color .2s, border-color .2s;
    z-index: 2;
    min-width: 140px;
    text-align: center;
}
.ws-buffer-err {
    color: oklch(var(--er));
    border-color: oklch(var(--er) / 0.4);
    animation: ws-shake .22s ease-in-out;
}
`;
