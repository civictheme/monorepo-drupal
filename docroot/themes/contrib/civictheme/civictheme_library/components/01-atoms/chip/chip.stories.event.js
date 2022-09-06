// phpcs:ignoreFile
/**
 * @file
 * Chip component event check.
 */

function customAlert() {
  alert('Chip dismiss event'); // eslint-disable-line no-alert
}

document.querySelectorAll('.civictheme-chip').forEach((el) => {
  el.addEventListener('civictheme.chip.dismiss', customAlert);
});
