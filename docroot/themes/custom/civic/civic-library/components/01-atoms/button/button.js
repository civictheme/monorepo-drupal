if (document.querySelector('[data-component-name="button"]')) {
  document.querySelector('[data-component-name="button"]').addEventListener('click', () => {
    // eslint-disable-next-line no-alert
    alert('Triggered example click event for the Button');
  });
}

if (document.querySelector('[data-component-name="link-button"]')) {
  document.querySelector('[data-component-name="link-button"]').addEventListener('click', () => {
    // eslint-disable-next-line no-alert
    alert('Triggered example click event for the Link Button');
  });
}
