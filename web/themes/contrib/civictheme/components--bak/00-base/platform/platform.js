// phpcs:ignoreFile
/**
 * @file
 * Platform utility.
 */

function CivicThemePlatform(el) {
  function iOS() {
    return [
      'iPad Simulator',
      'iPhone Simulator',
      'iPod Simulator',
      'iPad',
      'iPhone',
      'iPod',
    ].includes(navigator.platform)
    // iPad on iOS 13 detection
    || (navigator.userAgent.includes('Mac') && 'ontouchend' in document);
  }

  if (iOS()) {
    el.dataset.platform = 'ios';
  }
}

document.querySelectorAll('[data-platform]').forEach((el) => {
  new CivicThemePlatform(el);
});
