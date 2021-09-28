if (document.querySelector('[data-component-name="header-notifications"]')) {
  document.querySelector('[data-component-name="header-notifications"] .civic-header-notifications__close-icon').addEventListener('click', () => {
    // eslint-disable-next-line no-alert
    alert('Triggered example click event for header-notifications close button.');
  });
}
