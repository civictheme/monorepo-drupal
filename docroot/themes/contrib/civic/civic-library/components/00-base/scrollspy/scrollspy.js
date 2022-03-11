/**
 * @file
 * Scrollspy component.
 *
 * Adds '.civic-scrollspy-scrolled' class to an element whose
 * data-scrollspy-offset attribute's value is more than a vertical window scroll.
 */
function CivicScrollspy(el) {
  if (el.getAttribute('data-scrollspy') === 'true' || this.el) {
    return;
  }

  this.el = el;
  this.offset = this.el.hasAttribute('data-scrollspy-offset') ? this.el.getAttribute('data-scrollspy-offset') : null;

  document.addEventListener('scroll', CivicScrollspy.prototype.scrollEvent.bind(this));

  // Mark as initialized.
  this.el.setAttribute('data-scrollspy', 'true');
}

/**
 * Event handler for the scroll.
 */
CivicScrollspy.prototype.scrollEvent = function () {
  if (window.scrollY > this.offset) {
    this.el.classList.add('civic-scrollspy-scrolled');
  } else {
    this.el.classList.remove('civic-scrollspy-scrolled');
  }
};

document.querySelectorAll('[data-scrollspy]').forEach((el) => {
  new CivicScrollspy(el);
});
