// phpcs:ignoreFile
/**
 * @file
 * Skip to target utility.
 */

function CivicThemeSkipToTarget(el) {
  this.el = el;
  this.targetId = this.el.getAttribute('href');

  if (this.targetId) {
    this.targetEl = document.querySelector(this.targetId);

    this.el.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();

      this.targetEl.setAttribute('tabindex', '1');
      this.targetEl.focus();
      this.targetEl.scrollIntoView(true);
      this.targetEl.setAttribute('tabindex', '-1');
    });
  }
}

document.querySelectorAll('[data-skip-to-target]').forEach((el) => {
  new CivicThemeSkipToTarget(el);
});
