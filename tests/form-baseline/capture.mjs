#!/usr/bin/env node
/**
 * Captures normalised form markup baselines for the form rendering refactor.
 *
 * Fetches each configured page, extracts its <form> elements, strips volatile
 * values (form build ids, tokens, Twig debug comments) and writes one file
 * per page to baseline/. Diff these files while refactoring form rendering to
 * see exactly what changed in the markup.
 *
 * Usage:
 *   node capture.mjs [--out <dir>]
 *
 * Environment:
 *   BASELINE_BASE_URL  Base URL (default: http://civictheme.docker.amazee.io).
 */

import { mkdirSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const here = dirname(fileURLToPath(import.meta.url));
const baseUrl = (process.env.BASELINE_BASE_URL || 'http://civictheme.docker.amazee.io').replace(/\/$/, '');
const outDir = process.argv.includes('--out')
  ? process.argv[process.argv.indexOf('--out') + 1]
  : join(here, 'baseline');

const pages = [
  { name: 'webform-test-fields', path: '/form/civictheme-test-webform-fields' },
  { name: 'contact', path: '/form/contact' },
  { name: 'enquiry', path: '/enquiry' },
  { name: 'feedback', path: '/feedback' },
  { name: 'core-form-elements', path: '/civictheme-core-form-elements' },
];

function extractForms(html) {
  return html.match(/<form[^>]*>[\s\S]*?<\/form>/g) || [];
}

function normalise(html) {
  return html
    // Twig debug and component boundary comments.
    .replace(/<!--[\s\S]*?-->/g, '')
    // Volatile values.
    .replace(/(name="form_build_id"[^>]*value=")[^"]*(")/g, '$1FORM_BUILD_ID$2')
    .replace(/(value=")form-[^"]*(")/g, '$1FORM_BUILD_ID$2')
    .replace(/(name="form_token"[^>]*value=")[^"]*(")/g, '$1FORM_TOKEN$2')
    // One tag per line, collapse intra-tag whitespace runs.
    .replace(/>\s+</g, '>\n<')
    .split('\n')
    .map((line) => line.replace(/\s+/g, ' ').trim())
    .filter((line) => line !== '')
    .join('\n');
}

mkdirSync(outDir, { recursive: true });

let failures = 0;
for (const page of pages) {
  const url = baseUrl + page.path;
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }
    const forms = extractForms(await response.text());
    if (forms.length === 0) {
      throw new Error('no <form> element found');
    }
    const content = forms.map(normalise).join('\n\n<!-- ===== next form ===== -->\n\n');
    const file = join(outDir, `${page.name}.html`);
    writeFileSync(file, content + '\n');
    console.log(`${page.name}: ${forms.length} form(s) -> ${file}`);
  }
  catch (error) {
    failures += 1;
    console.error(`${page.name}: FAILED (${error.message}) [${url}]`);
  }
}

process.exit(failures > 0 ? 1 : 0);
