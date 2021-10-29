// Set focous on top of page element.
const backToTop = document.querySelector('.civic-back-to-top__button');
if (backToTop !== null) {
  backToTop.addEventListener('click', () => {
    const topOfPage = document.getElementById('#civic-top-of-page');
    if (topOfPage !== null) {
      topOfPage.focus();
    }
  }, false);
}
