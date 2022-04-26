/**
 * Handles views exposed filter forms providing auto-submit functionality.
 */
Drupal.behaviors.civic_ajax_views = {
  // eslint-disable-next-line no-unused-vars
  attach: function attach(context, settings) {
    // eslint-disable-next-line no-undef
    const $form = jQuery('[data-civic-filter]', context).once('civicAjaxView');
    if ($form.length === 0) {
      return;
    }
    let debounce;
    // Button submit handler for both large and basic filter types.
    const buttonSubmitHandler = () => {
      let $filter = $form.find('[data-component-name="civic-large-filter"]');
      let isAutosubmit;
      if ($filter.length > 0) {
        isAutosubmit = typeof $filter.attr('data-large-filter-auto-submit') !== 'undefined' && $filter.attr('data-large-filter-auto-submit') === 'true';
      } else {
        $filter = $form.find('[data-component-name="civic-basic-filter"]');
        isAutosubmit = $filter.length > 0;
      }
      if (isAutosubmit === true) {
        // We do not want to submit on every click, we want user to be able
        // to select several checkboxes or radio buttons without submitting.
        const timeout = $form.attr('data-civic-filter-ajax-submit-timeout') !== null ? Number($form.attr('data-civic-filter-ajax-submit-timeout')) : 500;
        if (timeout > 0) {
          if (typeof debounce !== 'undefined') {
            clearTimeout(debounce);
          }
          debounce = setTimeout(() => {
            $form.find('[type="submit"]').trigger('click');
          }, timeout);
        }
        else {
          $form.find('[type="submit"]').trigger('click');
        }
      }
    };
    const filterType = $form.attr('data-civic-filter-type');
    const ajaxForm = $form.attr('data-civic-filter-ajax') === 'true';
    if (filterType === 'large') {
      if (ajaxForm) {
        // Attach reload of view results in with redrawing of filters for
        // ajax forms.
        $form
          .find('[data-component-name="civic-large-filter"]')
          // Custom event from civic large filter.
          .on('civicLargeFilterChange', buttonSubmitHandler);
        // Stop clear filter function from submitting form.
        $form
          .find('[data-large-filter-clear]')
          .on('click', (e) => e.preventDefault());
      } else {
        // For non-ajax forms add click listener to dismissable filter chips
        // so page is reloaded with correct view results when clicked.
        // Other than this rely on clicking apply button to update view
        // results.
        $form
          .find('[data-component-name="civic-large-filter"]')
          // Custom event from civic large filter.
          .on('civicLargeFilterChange', () => {
            // Civic large filter redraws selected filters on each change in
            // the dropdown, when it redraws them we wish to re-add the button
            // submit handler to them so dismissing a filter chip reloads the
            // page.
            $form
              .find('[data-civic-filter-chip]')
              .on('click', buttonSubmitHandler);
          });
      }
    } else {
      $form
        .find('[data-component-name="filter-chip"] input')
        .on('change', buttonSubmitHandler);
    }
  },
};
