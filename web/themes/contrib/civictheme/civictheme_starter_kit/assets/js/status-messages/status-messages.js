// CivicTheme status-messages.js
(function ($, Drupal) {
  Drupal.behaviors.ctFocusErrorSummary = {
    attach: function (context, settings) {
      // Wait for DOM update after AJAX or normal load
      setTimeout(function () {
        // Find any element with id starting with 'error-summary-'
        var $error = $('[id^="error-summary-"]', context).first();
        if ($error.length) {
          $error.attr('tabindex', '-1'); // ensure focusable (in case)
          $error.focus();
        }
      }, 30);
    }
  };
})(jQuery, Drupal);
