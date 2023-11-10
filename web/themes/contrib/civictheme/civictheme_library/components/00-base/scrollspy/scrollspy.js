// phpcs:ignoreFile
/**
 * @file
 * Scrollspy component.
 *
 * Adds '.ct-scrollspy-scrolled' class to an element whose
 * data-scrollspy-offset attribute's value is more than a vertical window
 * scroll.
 */
function CivicThemeScrollspy(el) {
  if (el.getAttribute('data-scrollspy') === 'true' || this.el) {
    return;
  }

  this.el = el;
  this.offset = this.el.hasAttribute('data-scrollspy-offset') ? this.el.getAttribute('data-scrollspy-offset') : null;

  document.addEventListener('scroll', CivicThemeScrollspy.prototype.scrollEvent.bind(this));

  // Mark as initialized.
  this.el.setAttribute('data-scrollspy', 'true');
}

/**
 * Event handler for the scroll.
 */
CivicThemeScrollspy.prototype.scrollEvent = function () {
  if (window.scrollY > this.offset) {
    this.el.classList.add('ct-scrollspy-scrolled');
  } else {
    this.el.classList.remove('ct-scrollspy-scrolled');
  }
};

document.querySelectorAll('[data-scrollspy]').forEach((el) => {
  new CivicThemeScrollspy(el);
});
