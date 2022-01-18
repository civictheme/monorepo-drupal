if (document.querySelector('[data-component-name="demo-button"]')) {
  document.querySelector('[data-component-name="demo-button"]').addEventListener('click', () => {
    // eslint-disable-next-line no-alert
    alert('Triggered example click event for the Demo Button');
  });
}
