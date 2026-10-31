#!/usr/bin/env node
/**
 * axe-core accessibility scans of CivicTheme form pages.
 *
 * Drives a system Chrome/Chromium via puppeteer-core (the same approach as
 * the visual regression CI job), injects axe-core into each configured page
 * and reports WCAG violations. Scenarios may run actions first (fill, click)
 * to reach states such as validation errors or later wizard pages.
 *
 * Usage:
 *   npm install
 *   npm test
 *
 * Environment:
 *   A11Y_BASE_URL              Base URL (default: from a11y.config.json).
 *   PUPPETEER_EXECUTABLE_PATH  Chrome/Chromium binary (autodetected if unset).
 *   A11Y_FAIL_IMPACTS          Comma-separated impacts that fail the run
 *                              (default: critical,serious).
 *   A11Y_SCENARIO              Run a single scenario by name.
 */

import { createRequire } from 'node:module';
import { existsSync, mkdirSync, readFileSync, writeFileSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import puppeteer from 'puppeteer-core';

const here = dirname(fileURLToPath(import.meta.url));
const require = createRequire(import.meta.url);
const axePath = require.resolve('axe-core/axe.min.js');

const config = JSON.parse(readFileSync(join(here, 'a11y.config.json'), 'utf8'));
const baseUrl = (process.env.A11Y_BASE_URL || config.baseUrl).replace(/\/$/, '');
const failImpacts = (process.env.A11Y_FAIL_IMPACTS || 'critical,serious')
  .split(',')
  .map((impact) => impact.trim())
  .filter(Boolean);
const onlyScenario = process.env.A11Y_SCENARIO || null;

function findChrome() {
  const fromEnv = process.env.PUPPETEER_EXECUTABLE_PATH || process.env.CHROME_PATH;
  if (fromEnv) {
    return fromEnv;
  }
  const candidates = [
    '/usr/bin/chromium-browser',
    '/usr/bin/chromium',
    '/usr/bin/google-chrome-stable',
    '/usr/bin/google-chrome',
    '/snap/bin/chromium',
  ];
  const found = candidates.find((candidate) => existsSync(candidate));
  if (!found) {
    throw new Error('No Chrome/Chromium binary found. Set PUPPETEER_EXECUTABLE_PATH.');
  }
  return found;
}

async function runScenario(browser, scenario) {
  const page = await browser.newPage();
  try {
    await page.setViewport({ width: 1280, height: 900 });
    const response = await page.goto(baseUrl + scenario.path, {
      waitUntil: 'networkidle2',
      timeout: 60000,
    });
    const status = response ? response.status() : 0;
    if (status >= 400) {
      return { name: scenario.name, url: baseUrl + scenario.path, error: `HTTP ${status}` };
    }

    for (const action of scenario.actions || []) {
      if (action.type === 'fill') {
        await page.type(action.selector, action.value);
      }
      else if (action.type === 'click') {
        await Promise.all([
          page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 60000 }),
          page.click(action.selector),
        ]);
      }
      else {
        throw new Error(`Unknown action type "${action.type}" in scenario "${scenario.name}".`);
      }
    }

    await page.addScriptTag({ path: axePath });
    const result = await page.evaluate(
      async (include, runOnly) => {
        const context = include ? { include } : document;
        // eslint-disable-next-line no-undef
        return await axe.run(context, { resultTypes: ['violations'], runOnly });
      },
      scenario.include || null,
      config.runOnly || null,
    );

    return {
      name: scenario.name,
      url: page.url(),
      violations: result.violations.map((violation) => ({
        id: violation.id,
        impact: violation.impact,
        help: violation.help,
        helpUrl: violation.helpUrl,
        nodes: violation.nodes.length,
        targets: violation.nodes.slice(0, 5).map((node) => node.target.join(' ')),
      })),
    };
  }
  catch (error) {
    return { name: scenario.name, url: baseUrl + scenario.path, error: String(error) };
  }
  finally {
    await page.close();
  }
}

const browser = await puppeteer.launch({
  executablePath: findChrome(),
  headless: true,
  args: ['--no-sandbox', '--disable-dev-shm-usage'],
});

const scenarios = config.scenarios.filter(
  (scenario) => !onlyScenario || scenario.name === onlyScenario,
);
if (scenarios.length === 0) {
  console.error(`No scenario named "${onlyScenario}".`);
  process.exit(2);
}

const results = [];
for (const scenario of scenarios) {
  process.stdout.write(`Scanning ${scenario.name} ... `);
  const result = await runScenario(browser, scenario);
  results.push(result);
  if (result.error) {
    console.log(`ERROR (${result.error})`);
  }
  else {
    console.log(`${result.violations.length} violation(s)`);
  }
}
await browser.close();

const reportDir = resolve(here, '.logs');
mkdirSync(reportDir, { recursive: true });
const reportPath = join(reportDir, 'report.json');
writeFileSync(reportPath, JSON.stringify({ baseUrl, date: new Date().toISOString(), results }, null, 2));

let failures = 0;
let errors = 0;
for (const result of results) {
  if (result.error) {
    errors += 1;
    console.log(`\n[${result.name}] SCAN ERROR: ${result.error}`);
    continue;
  }
  for (const violation of result.violations) {
    const failing = failImpacts.includes(violation.impact);
    if (failing) {
      failures += 1;
    }
    console.log(
      `\n[${result.name}] ${failing ? 'FAIL' : 'warn'} ${violation.id} (${violation.impact}), ` +
      `${violation.nodes} node(s): ${violation.help}\n  ${violation.targets.join('\n  ')}`,
    );
  }
}

console.log(`\nReport written to ${reportPath}`);
console.log(`Failing impacts: ${failImpacts.join(', ')}. Failures: ${failures}. Scan errors: ${errors}.`);
process.exit(failures > 0 || errors > 0 ? 1 : 0);
