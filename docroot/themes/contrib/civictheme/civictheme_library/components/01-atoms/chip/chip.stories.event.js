// phpcs:ignoreFile
/**
 * @file
 * Chip component event binding example.
 */

function storiesAlert() {
  alert(`Chip dismiss example event was triggered for Chip with content "${this.textContent.trim()}"`); // eslint-disable-line no-alert
}

// Example of how to bind to the 'ct.chip.dismiss' event triggered on Chip
// with 'data-chip-dismiss' attribute.
document.querySelectorAll('.ct-chip').forEach((el) => {
  el.addEventListener('ct.chip.dismiss', storiesAlert);
});
