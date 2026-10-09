// Regenerates the README / GitHub social-preview images from real components:
// serves the docs, opens /docs/hero/{light,dark} in headless Chrome and saves
// 1280×640 screenshots (at 2× pixel density) to .github/images/.
//
//   npm run docs:build && npm run docs:hero        # CHROME_PATH=/path/to/chrome to pick a browser
import { spawn, execFileSync } from 'node:child_process';
import { existsSync, mkdirSync } from 'node:fs';

const port = Number(process.env.HERO_PORT || 8123);
const out = '.github/images';
const candidates = [process.env.CHROME_PATH, 'google-chrome', 'google-chrome-stable', 'chromium', 'chromium-browser',
    '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome'].filter(Boolean);

const chrome = candidates.find((bin) => {
    try { execFileSync(bin, ['--version'], { stdio: 'ignore' }); return true; } catch { return existsSync(bin); }
});
if (!chrome) {
    console.error('No Chrome/Chromium found. Set CHROME_PATH.');
    process.exit(1);
}

const server = spawn('php', ['vendor/bin/testbench', 'serve', `--port=${port}`], { stdio: 'ignore' });
const stop = () => server.kill();
process.on('exit', stop);

const base = `http://127.0.0.1:${port}/docs/hero`;
for (let i = 0; i < 50; i++) {
    try { if ((await fetch(`${base}/light`)).ok) break; } catch {}
    await new Promise((resolve) => setTimeout(resolve, 200));
}

mkdirSync(out, { recursive: true });
for (const [theme, file] of [['light', 'hero.png'], ['dark', 'hero-dark.png']]) {
    execFileSync(chrome, [
        '--headless=new', '--no-sandbox', '--hide-scrollbars', '--force-device-scale-factor=2',
        '--window-size=1280,640', '--virtual-time-budget=5000', `--screenshot=${out}/${file}`, `${base}/${theme}`,
    ], { stdio: 'ignore' });
    console.log(`✓ ${out}/${file}`);
}
stop();
