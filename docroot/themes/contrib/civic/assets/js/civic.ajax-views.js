/**
 * Handles views exposed filter forms providing auto-submit functionality.
 */
Drupal.behaviors.civic_ajax_views = {
  // eslint-disable-next-line no-unused-vars
  attach: function attach(context, settings) {
    // eslint-disable-next-line no-undef
    const $form = jQuery('[data-civic-filter]', context).once('civicAjaxView');
    let debounce;
    // Submit handler for both large and basic filter types.
    const submitHandler = () => {
      // We do not want to submit on every click, we want user to be able
      // to select several checkboxes or radio buttons without submitting.
      if (typeof debounce !== 'undefined') {
        clearTimeout(debounce);
      }
      debounce = setTimeout(() => {
        $form.find('[type="submit"]').trigger('click');
      }, 500);
    };
    if ($form.length > 0) {
      const filterType = $form.attr('data-civic-filter-type');
      const ajaxForm = $form.attr('data-civic-filter-ajax') === 'true';
      if (filterType === 'large') {
        if (ajaxForm) {
          // Attach reload of view results in with redrawing of filters for
          // ajax forms.
          $form
            .find('.civic-large-filter')
            // Custom event from civic large filter.
            .on('civic-large-filter-change', submitHandler);
          // Stop clear filter function from submitting form.
          $form
            .find('.civic-large-filter__clear-all')
            .on('click', (e) => { e.preventDefault(); });
        } else {
          // For non-ajax forms add click listener to dismissable filter chips
          // so page is reloaded with correct view results when clicked.
          // Other than this rely on clicking apply button to update view
          // results.
          $form
            .find('.civic-large-filter')
            // Custom event from civic large filter.
            .on('civic-large-filter-change', () => {
              $form
                .find('[data-civic-filter-chip]')
                .on('click', submitHandler);
            });
        }
      } else {
        $form
          .find('[data-component-name="filter-chip"] input')
          .on('change', submitHandler);
      }
    }
  },
};
