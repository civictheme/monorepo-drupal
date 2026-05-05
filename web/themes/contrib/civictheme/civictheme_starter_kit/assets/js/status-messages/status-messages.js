// CivicTheme status-messages.js
(function (Drupal) {
  Drupal.behaviors.ctFocusErrorSummary = {
    attach: function () {
      // Use setTimeout to ensure the error element is rendered in the DOM.
      setTimeout(function () {
        // Always search from document since error messages may be outside
        // the current behavior context.
        var error = document.querySelector('[id^="error-summary-"]');
        if (error && !error.hasAttribute('data-ct-error-focused')) {
          error.setAttribute('data-ct-error-focused', 'true');
          error.setAttribute('tabindex', '-1');
          error.focus();
          error.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }, 30);
    },
  };
})(Drupal);
