// phpcs:ignoreFile
/**
 * @file
 * Chip component event check.
 */

document.querySelectorAll('.civictheme-chip').forEach((el) => {
  el.addEventListener('civictheme.chip.dismiss', function(event) {
    return alert('Chip dismiss event');
  });
});
