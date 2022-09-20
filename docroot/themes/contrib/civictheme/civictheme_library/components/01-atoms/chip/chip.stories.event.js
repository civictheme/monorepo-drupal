// phpcs:ignoreFile
/**
 * @file
 * Chip component event check.
 */

function customAlert() {
  alert('Chip dismiss event'); // eslint-disable-line no-alert
}

document.querySelectorAll('.ct-chip').forEach((el) => {
  el.addEventListener('ct.chip.dismiss', customAlert);
});
