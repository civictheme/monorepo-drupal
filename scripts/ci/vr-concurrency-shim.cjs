/**
 * Temporary CI shim for @richardgaunt/visual-regression-for-drupal.
 *
 * The tool derives its puppeteer-cluster concurrency from os.cpus() and
 * os.totalmem() with no override (min(cpus*16, totalGB/0.15, 100)). On a
 * 4-vCPU GitHub runner that is 64 concurrent Chrome pages against the single
 * apache container — pages starve each other past the cluster's 20s per-task
 * timeout and ~95% of screenshots error (while the tool still exits 0).
 *
 * Preload via NODE_OPTIONS="--require .../vr-concurrency-shim.cjs" to present
 * a small machine: min(1*16, floor(0.9/0.15), 100) = 6 concurrent pages.
 * Drop this once the tool grows a --concurrency option.
 */
const os = require('os');

const realCpus = os.cpus.bind(os);
os.cpus = () => realCpus().slice(0, 1);
os.totalmem = () => Math.floor(0.9 * 1024 * 1024 * 1024);
