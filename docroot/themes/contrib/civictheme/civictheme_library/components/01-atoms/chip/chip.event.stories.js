// phpcs:ignoreFile
/**
 * @file
 * Chip component event binding example.
 *
 * Disregard 'export default' - it is required by Storybook only.
 */

export default {
  title: 'Atoms/Chip example event',
};

document.addEventListener('DOMContentLoaded', () => {
  function storiesAlert() {
    alert(`Chip dismiss example event was triggered for Chip with content "${this.textContent.trim()}"`); // eslint-disable-line no-alert
  }

  // Example of how to bind to the 'ct.chip.dismiss' event triggered on Chip
  // with 'data-chip-dismiss' attribute.
  document.querySelectorAll('.ct-chip').forEach((el) => {
    if (!el.hasAttribute('story-processed')) {
      el.addEventListener('ct.chip.dismiss', storiesAlert);
      el.setAttribute('story-processed', 1);
    }
  });
});
