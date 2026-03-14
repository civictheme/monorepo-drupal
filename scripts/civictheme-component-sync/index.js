#!/usr/bin/env node

const fs = require('fs');
const path = require('path');

// Load .env.local if it exists (without requiring dotenv dependency).
const envLocalPath = path.resolve(__dirname, '../../.env.local');
if (fs.existsSync(envLocalPath)) {
  for (const line of fs.readFileSync(envLocalPath, 'utf8').split('\n')) {
    const trimmed = line.trim();
    if (!trimmed || trimmed.startsWith('#')) {
      continue;
    }
    const eqIndex = trimmed.indexOf('=');
    if (eqIndex === -1) {
      continue;
    }
    const key = trimmed.slice(0, eqIndex);
    // Only set if not already in environment (real env takes precedence).
    if (!(key in process.env)) {
      process.env[key] = trimmed.slice(eqIndex + 1);
    }
  }
}

const THEME_COMPONENTS = process.env.CIVICTHEME_COMPONENTS_DIR
  || path.resolve(__dirname, '../../web/themes/contrib/civictheme/components');
const UIKIT_COMPONENTS = process.env.CIVICTHEME_UIKIT_COMPONENTS_DIR
  || process.argv[2];

if (!UIKIT_COMPONENTS) {
  console.error('No target directory specified.');
  console.error('Set CIVICTHEME_UIKIT_COMPONENTS_DIR or pass as argument:');
  console.error('  export CIVICTHEME_UIKIT_COMPONENTS_DIR=/path/to/civictheme-uikit/packages/sdc/components');
  console.error('  npm run uikit-sync');
  process.exit(1);
}

if (!fs.existsSync(THEME_COMPONENTS)) {
  console.error(`Source directory not found: ${THEME_COMPONENTS}`);
  process.exit(1);
}

if (!fs.existsSync(UIKIT_COMPONENTS)) {
  console.error(`Target directory not found: ${UIKIT_COMPONENTS}`);
  process.exit(1);
}

console.log(`Watching: ${THEME_COMPONENTS}`);
console.log(`Syncing to: ${UIKIT_COMPONENTS}`);
console.log('');

let debounceTimer = null;
const pendingChanges = new Set();

function syncFile(relativePath) {
  const src = path.join(THEME_COMPONENTS, relativePath);
  const dest = path.join(UIKIT_COMPONENTS, relativePath);

  if (!fs.existsSync(src)) {
    // File was deleted.
    if (fs.existsSync(dest)) {
      fs.unlinkSync(dest);
      console.log(`  Deleted: ${relativePath}`);
    }
    return;
  }

  const destDir = path.dirname(dest);
  if (!fs.existsSync(destDir)) {
    fs.mkdirSync(destDir, { recursive: true });
  }

  fs.copyFileSync(src, dest);
  console.log(`  Synced: ${relativePath}`);
}

function processPendingChanges() {
  if (pendingChanges.size === 0) {
    return;
  }

  const timestamp = new Date().toLocaleTimeString();
  console.log(`[${timestamp}] Syncing ${pendingChanges.size} file(s)...`);

  for (const relativePath of pendingChanges) {
    try {
      syncFile(relativePath);
    }
    catch (err) {
      console.error(`  Error syncing ${relativePath}: ${err.message}`);
    }
  }

  pendingChanges.clear();
  console.log('');
}

fs.watch(THEME_COMPONENTS, { recursive: true }, (eventType, filename) => {
  if (!filename) {
    return;
  }

  // Ignore hidden files and common non-component files.
  if (filename.startsWith('.') || filename.includes('node_modules')) {
    return;
  }

  pendingChanges.add(filename);

  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(processPendingChanges, 200);
});

console.log('Watching for changes... (Ctrl+C to stop)\n');
