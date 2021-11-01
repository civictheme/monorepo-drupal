// Set focous on top of page element.
// @todo Check window scroll to show/hide back to top button.
document.querySelector('[data-component-name="back-to-top"]').addEventListener('click', () => {
  const topOfPage = document.getElementById('#civic-top-of-page');
  if (topOfPage !== null) {
    topOfPage.focus();
  }
}, false);
