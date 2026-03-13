#!/usr/bin/env node

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const THEME_DIR = path.resolve(__dirname, '../../web/themes/contrib/civictheme');
const yaml = require(path.join(THEME_DIR, 'node_modules/js-yaml'));
const WEB_DIR = path.resolve(__dirname, '../../web');
const CONFIG_DIRS = ['config/install'];
const INFO_PATH = path.join(THEME_DIR, 'civictheme.info.yml');

function getModuleDependencies(configDir) {
  const modules = new Set();
  const files = fs.readdirSync(configDir).filter(f => f.endsWith('.yml'));

  for (const file of files) {
    const filePath = path.join(configDir, file);
    const content = fs.readFileSync(filePath, 'utf8');
    const parsed = yaml.load(content);

    if (parsed?.dependencies?.module) {
      for (const mod of parsed.dependencies.module) {
        modules.add(mod);
      }
    }
  }

  return modules;
}

function getInfoDependencies() {
  const content = fs.readFileSync(INFO_PATH, 'utf8');
  const parsed = yaml.load(content);

  const modules = new Set();
  if (parsed?.dependencies) {
    for (const dep of parsed.dependencies) {
      const parts = dep.split(':');
      modules.add(parts[parts.length - 1]);
    }
  }

  return modules;
}

function findModulePath(mod) {
  try {
    const output = execSync(
      `find "${WEB_DIR}/core" "${WEB_DIR}/modules/contrib" "${WEB_DIR}/modules/custom" -name "${mod}.info.yml" -type f 2>/dev/null || true`,
      { encoding: 'utf8' }
    ).trim();

    if (output) {
      return output.split('\n')[0];
    }
  } catch {
    // ignore
  }

  return null;
}

function getModuleType(mod) {
  const filePath = findModulePath(mod);
  if (!filePath) {
    return { type: 'unknown' };
  }

  const relative = path.relative(WEB_DIR, filePath);

  if (relative.startsWith('core')) {
    return { type: 'core' };
  }

  if (relative.startsWith('modules/contrib')) {
    // e.g. modules/contrib/search_api/modules/search_api_db/search_api_db.info.yml
    // or   modules/contrib/paragraphs/paragraphs.info.yml
    const parts = relative.split(path.sep);
    // parts[2] is the project name (directory directly under contrib/)
    const project = parts[2];
    return { type: 'contrib', project };
  }

  if (relative.startsWith('modules/custom')) {
    return { type: 'custom' };
  }

  return { type: 'unknown' };
}

function formatDependency(mod, moduleType) {
  if (moduleType.type === 'core') {
    return `drupal:${mod}`;
  }
  if (moduleType.type === 'contrib') {
    return `${moduleType.project}:${mod}`;
  }
  return mod;
}

function getAllConfigModules() {
  const allModules = new Set();

  for (const dir of CONFIG_DIRS) {
    const fullPath = path.join(THEME_DIR, dir);
    if (!fs.existsSync(fullPath)) {
      continue;
    }
    for (const mod of getModuleDependencies(fullPath)) {
      allModules.add(mod);
    }
  }

  return allModules;
}

function getMissingModules() {
  const configModules = getAllConfigModules();
  const infoModules = getInfoDependencies();
  const missing = [...configModules].filter(m => !infoModules.has(m)).sort();

  return missing.map(mod => {
    const moduleType = getModuleType(mod);
    return {
      module: mod,
      ...moduleType,
      dependency: formatDependency(mod, moduleType),
    };
  });
}

function check() {
  const missing = getMissingModules();

  if (missing.length === 0) {
    console.log('All config module dependencies are listed in info.yml.');
    process.exit(0);
  }

  console.log('Missing module dependencies in info.yml:\n');

  const core = missing.filter(m => m.type === 'core');
  const contrib = missing.filter(m => m.type === 'contrib');
  const custom = missing.filter(m => m.type === 'custom');
  const unknown = missing.filter(m => m.type === 'unknown');

  if (core.length > 0) {
    console.log('Core:');
    for (const m of core) {
      console.log(`  - ${m.dependency}`);
    }
    console.log();
  }

  if (contrib.length > 0) {
    console.log('Contrib:');
    for (const m of contrib) {
      console.log(`  - ${m.dependency}`);
    }
    console.log();
  }

  if (custom.length > 0) {
    console.log('Custom (UNEXPECTED):');
    for (const m of custom) {
      console.log(`  - ${m.dependency}`);
    }
    console.log();
  }

  if (unknown.length > 0) {
    console.log('Unknown (not found in codebase):');
    for (const m of unknown) {
      console.log(`  - ${m.module}`);
    }
    console.log();
  }

  process.exit(1);
}

function fix() {
  const missing = getMissingModules();

  if (missing.length === 0) {
    console.log('All config module dependencies are already listed in info.yml.');
    return;
  }

  const content = fs.readFileSync(INFO_PATH, 'utf8');
  const lines = content.split('\n');

  // Find the dependencies block and its last entry before the next top-level key.
  const depIndex = lines.findIndex(l => /^dependencies:/.test(l));
  if (depIndex === -1) {
    console.error('Could not find dependencies: block in info.yml');
    process.exit(1);
  }

  // Find the last "  - ..." line in the dependencies block.
  let lastDepLine = depIndex;
  for (let i = depIndex + 1; i < lines.length; i++) {
    if (/^\s+-\s+/.test(lines[i])) {
      lastDepLine = i;
    } else if (/^\S/.test(lines[i]) && lines[i].trim() !== '') {
      break;
    }
  }

  const newEntries = missing.map(m => `  - ${m.dependency}`);
  lines.splice(lastDepLine + 1, 0, ...newEntries);

  fs.writeFileSync(INFO_PATH, lines.join('\n'));

  console.log(`Added ${missing.length} dependencies to info.yml:\n`);
  for (const m of missing) {
    console.log(`  - ${m.dependency}`);
  }
}

const mode = process.argv[2];

if (mode === 'check') {
  check();
} else if (mode === 'fix') {
  fix();
} else {
  console.log('Usage: civictheme-dependency-checker <check|fix>');
  console.log('  check  - Show missing dependencies, exit 1 if any found');
  console.log('  fix    - Add missing dependencies to info.yml');
  process.exit(1);
}
