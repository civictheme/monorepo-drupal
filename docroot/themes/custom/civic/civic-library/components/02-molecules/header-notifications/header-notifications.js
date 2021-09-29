if (document.querySelector('[data-component-name="header-notifications"]')) {
  document.querySelector('[data-component-name="header-notifications"] .civic-header-notifications__close-icon').addEventListener('click', () => {
    // eslint-disable-next-line no-alert
    // @todo Implement saving of the notification state in local storage.
    alert('Triggered example click event for header-notifications close button.');
  });
}
