const header = document.querySelector('[data-component-name="header"]');
const backToTop = document.querySelector('[data-component-name="back-to-top"]');
const topIdExists = document.getElementById('civic-top-of-page');
let siteHeaderHeight = 148;

// Helper function to show/hide back to top button on scroll.
function scrollFunction(headerHeight) {
  const scrollTop = window.pageYOffset
    || document.documentElement.scrollTop
    || document.body.scrollTop
    || 0;

  if (scrollTop > headerHeight || document.documentElement.scrollTop > headerHeight) {
    if (backToTop.classList.contains('civic-back-to-top--fade-out')) {
      backToTop.classList.remove('civic-back-to-top--fade-out');
    }
    backToTop.classList.add('civic-back-to-top--fade-in');
  } else {
    if (backToTop.classList.contains('civic-back-to-top--fade-in')) {
      backToTop.classList.remove('civic-back-to-top--fade-in');
    }
    backToTop.classList.add('civic-back-to-top--fade-out');
  }
}

// Calculate header height.
if (header !== null) {
  siteHeaderHeight = header.offsetHeight;
}

// Check if top id exists in the page else keep back to top hidden.
if (topIdExists !== null) {
  window.onscroll = () => { scrollFunction(siteHeaderHeight); };
}
