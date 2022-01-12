Drupal.behaviors.civic_ajax_views = {
  // eslint-disable-next-line no-unused-vars
  attach: function attach(context, settings) {
    // eslint-disable-next-line no-undef
    const $form = jQuery(context).once('civicAjaxView');
    let debounce;
    $form
      .find('.civic-large-filter')
      .on('civic-large-filter-change', () => {
        // We do not want to submit on every click, we want user to be able
        // to select several checkboxes or radio buttons without submitting.
        if (typeof debounce !== 'undefined') {
          clearTimeout(debounce);
        }
        debounce = setTimeout(() => {
          $form.find('[type="submit"]').trigger('click');
        }, 500);
      });
    // Stop clear filter function from submitting form.
    $form
      .find('.civic-large-filter__clear-all')
      .on('click', (e) => { e.preventDefault(); });
  },
};
