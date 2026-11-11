import fs from 'node:fs';
import path from 'node:path';

const buildDir = path.resolve('public/build');
const manifest = path.join(buildDir, 'manifest.json');

if (! fs.existsSync(buildDir)) {
    console.error('ERROR: public/build was not created. Hostinger Output directory must be public/build (not dist).');
    process.exit(1);
}

if (! fs.existsSync(manifest)) {
    console.error('ERROR: public/build/manifest.json is missing after vite build.');
    process.exit(1);
}

const entries = fs.readdirSync(buildDir, { withFileTypes: true });
const hasAssets = entries.some((entry) => entry.isDirectory() || /\.(css|js)$/i.test(entry.name));

if (! hasAssets) {
    console.error('ERROR: public/build contains no CSS/JS assets.');
    process.exit(1);
}

console.log('Vite build OK → public/build/manifest.json');
console.log('Reminder: Laravel document root must be /public (not /public/build).');
