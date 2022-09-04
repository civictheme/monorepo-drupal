// phpcs:ignoreFile
/**
 * @file
 * Chip component event check.
 */

document.querySelectorAll('.civictheme-chip').forEach((el) => {
  el.addEventListener("civictheme.chip.dismiss", function(event) {
    alert("Chip dismiss event " + event.target.tagName);
  });
});
