#!/usr/bin/env bash
# Download the latest Quant Cloud database backup, decompress it, and report paths.
set -euo pipefail

if ! command -v qc >/dev/null 2>&1; then
  echo "Quant CLI (qc) is required but was not found. Install it first." >&2
  exit 1
fi

QUANT_ORG_NAME="${QUANT_ORG_NAME:-salsa-digital}"

if ! qc org select "${QUANT_ORG_NAME}" >/dev/null 2>&1; then
  echo "Failed to select Quant organisation '${QUANT_ORG_NAME}'. Run \`qc org list\` to verify access." >&2
  exit 1
fi

echo "Downloading database from Quant Cloud. If you need Lagoon, run \`ahoy download-db-lagoon\`."

QUANT_APP_NAME="${QUANT_APP_NAME:-${LAGOON_PROJECT:-qld-bsc}}"
# QUANT_APP_NAME is the canonical Quant application identifier; falls back to LAGOON_PROJECT for legacy workflows.
QUANT_ENVIRONMENT="${QUANT_ENVIRONMENT:-production}"
# Backward compatibility: expose LAGOON_PROJECT for legacy tooling until everything is Quant-aware.
if [ -z "${LAGOON_PROJECT:-}" ]; then
  export LAGOON_PROJECT="${QUANT_APP_NAME}"
fi

DREVOPS_DB_DIR="${DREVOPS_DB_DIR:-./.data}"
DREVOPS_DB_FILE="${DREVOPS_DB_FILE:-db.sql}"
QUANT_DOWNLOAD_DIR="${QUANT_DOWNLOAD_DIR:-./downloads}"
QUANT_BACKUP_POLL_INTERVAL="${QUANT_BACKUP_POLL_INTERVAL:-15}"
QUANT_BACKUP_TIMEOUT="${QUANT_BACKUP_TIMEOUT:-900}"
QUANT_BACKUP_DESCRIPTION="${QUANT_BACKUP_DESCRIPTION:-Database backup triggered by ahoy download-db on $(date -u +'%Y-%m-%dT%H:%M:%SZ')}"

mkdir -p "${DREVOPS_DB_DIR}" "${QUANT_DOWNLOAD_DIR}"

QC_BIN_REALPATH="$(python3 - <<'PY'
import os
import shutil
path = shutil.which('qc')
if not path:
    raise SystemExit('qc binary not found')
print(os.path.realpath(path))
PY
)"
QUANT_CLI_MODULE_DIR="$(dirname "${QC_BIN_REALPATH}")"
export QUANT_CLI_MODULE_DIR
export QUANT_APP_NAME QUANT_ENVIRONMENT QUANT_DOWNLOAD_DIR DREVOPS_DB_DIR DREVOPS_DB_FILE
export QUANT_BACKUP_POLL_INTERVAL QUANT_BACKUP_TIMEOUT QUANT_BACKUP_DESCRIPTION

NODE_OUTPUT="$(node --input-type=module <<'NODE'
import fs from 'fs';
import path from 'path';
import { pipeline } from 'stream/promises';
import { createGunzip } from 'zlib';
import { pathToFileURL } from 'url';

const hasGzipMagicNumber = (filePath) => {
  let fd;
  try {
    fd = fs.openSync(filePath, 'r');
    const buffer = Buffer.alloc(2);
    const bytesRead = fs.readSync(fd, buffer, 0, 2, 0);
    return bytesRead === 2 && buffer[0] === 0x1f && buffer[1] === 0x8b;
  } catch (error) {
    return false;
  } finally {
    if (fd !== undefined) {
      fs.closeSync(fd);
    }
  }
};

const moduleDir = process.env.QUANT_CLI_MODULE_DIR;
if (!moduleDir) {
  console.error('[quant] QUANT_CLI_MODULE_DIR not set. Cannot proceed.');
  process.exit(1);
}

const toNumber = (value, fallback) => {
  const num = Number(value);
  return Number.isFinite(num) && num > 0 ? num : fallback;
};

const pollIntervalSec = toNumber(process.env.QUANT_BACKUP_POLL_INTERVAL, 15);
const timeoutSec = toNumber(process.env.QUANT_BACKUP_TIMEOUT, 900);
const downloadDir = process.env.QUANT_DOWNLOAD_DIR || './downloads';
const decompressDir = process.env.DREVOPS_DB_DIR || './.data';
const decompressFile = process.env.DREVOPS_DB_FILE || 'db.sql';
const appName = process.env.QUANT_APP_NAME || process.env.QUANT_APPLICATION || process.env.LAGOON_PROJECT || '';
const envName = process.env.QUANT_ENVIRONMENT || '';
const description = process.env.QUANT_BACKUP_DESCRIPTION || `Database backup triggered by ahoy download-db on ${new Date().toISOString()}`;

const { ApiClient } = await import(pathToFileURL(path.join(moduleDir, 'utils/api.js')).href);
const client = await ApiClient.create();

const orgId = client.defaultOrganizationId;
const appId = appName || client.defaultApplicationId;
const envId = envName || client.defaultEnvironmentId;

if (!orgId || !appId || !envId) {
  console.error('[quant] Missing organization, application, or environment context. Use `qc login` and ensure defaults are set.');
  process.exit(1);
}

console.error(`[quant] Creating database backup for app "${appId}" environment "${envId}"...`);

const createResp = await client.backupManagementApi.createBackup(orgId, appId, envId, 'database', { description });
const backup = createResp.body || {};
const backupId = backup.id || backup.backupId;

if (!backupId) {
  console.error('[quant] API did not return a backup ID.');
  process.exit(1);
}

const normalizeStatus = (value) => (value || '').toLowerCase();
let lastStatus = normalizeStatus(backup.status) || 'requested';
console.error(`[quant] Backup requested (ID: ${backupId}). Initial status: ${lastStatus}.`);

const pollIntervalMs = Math.max(5, pollIntervalSec) * 1000;
const timeoutMs = Math.max(pollIntervalMs, timeoutSec * 1000);
const start = Date.now();

while (true) {
  const listResp = await client.backupManagementApi.listBackups(orgId, appId, envId, 'database');
  const backups = listResp.body?.backups ?? [];
  const current = backups.find((b) => (b.backupId || b.id) === backupId);

  if (current) {
    const status = normalizeStatus(current.status);
    if (status !== lastStatus) {
      console.error(`[quant] Backup status: ${status}`);
      lastStatus = status;
    }
    if (status === 'completed') {
      console.error('[quant] Backup completed.');
      break;
    }
    if (status === 'failed') {
      console.error('[quant] Backup failed.');
      process.exit(1);
    }
  } else {
    console.error('[quant] Backup not yet visible in list; waiting...');
  }

  if (Date.now() - start > timeoutMs) {
    console.error(`[quant] Backup did not complete within ${Math.round(timeoutMs / 1000)} seconds.`);
    process.exit(1);
  }

  await new Promise((resolve) => setTimeout(resolve, pollIntervalMs));
}

console.error('[quant] Requesting download URL...');
const downloadResp = await client.backupManagementApi.downloadBackup(orgId, appId, envId, 'database', backupId);
const downloadData = downloadResp.body || {};
const downloadUrl = downloadData.downloadUrl;
const filename = downloadData.filename || `${backupId}.sql.gz`;

if (!downloadUrl) {
  console.error('[quant] API did not return a download URL.');
  process.exit(1);
}

const outputDir = path.resolve(downloadDir);
fs.mkdirSync(outputDir, { recursive: true });
const downloadPath = path.join(outputDir, filename);

console.error(`[quant] Downloading backup to ${downloadPath}...`);
const response = await fetch(downloadUrl);
if (!response.ok || !response.body) {
  console.error(`[quant] Failed to download backup: HTTP ${response.status}`);
  process.exit(1);
}
const fileStream = fs.createWriteStream(downloadPath);
await pipeline(response.body, fileStream);
console.error('[quant] Download complete.');

const stats = fs.statSync(downloadPath);

const decompressedPath = path.resolve(decompressDir, decompressFile);
fs.mkdirSync(path.dirname(decompressedPath), { recursive: true });
fs.rmSync(decompressedPath, { force: true });

const lowerCaseFilename = filename.toLowerCase();
const backupIsLikelyGzip = lowerCaseFilename.endsWith('.gz') || lowerCaseFilename.endsWith('.gzip') || hasGzipMagicNumber(downloadPath);

if (backupIsLikelyGzip) {
  console.error(`[quant] Decompressing backup to ${decompressedPath}...`);
  await pipeline(
    fs.createReadStream(downloadPath),
    createGunzip(),
    fs.createWriteStream(decompressedPath)
  );
  console.error('[quant] Decompression complete.');
} else {
  console.error(`[quant] Backup is already uncompressed; copying to ${decompressedPath}...`);
  await pipeline(fs.createReadStream(downloadPath), fs.createWriteStream(decompressedPath));
  console.error('[quant] Copy complete.');
}

const toShellValue = (value) => {
  if (value === undefined || value === null) {
    return '';
  }
  if (typeof value === 'number') {
    return String(value);
  }
  return JSON.stringify(value);
};

console.log(`BACKUP_ID=${toShellValue(backupId)}`);
console.log(`DOWNLOAD_PATH=${toShellValue(downloadPath)}`);
console.log(`DECOMPRESSED_PATH=${toShellValue(decompressedPath)}`);
console.log(`DOWNLOAD_SIZE_BYTES=${toShellValue(stats.size)}`);
NODE
)"
if [ -z "${NODE_OUTPUT}" ]; then
  echo "Failed to download backup from Quant Cloud." >&2
  exit 1
fi

eval "${NODE_OUTPUT}"

echo "Quant backup ${BACKUP_ID} downloaded to ${DOWNLOAD_PATH}"
echo "Database decompressed to ${DECOMPRESSED_PATH}"
