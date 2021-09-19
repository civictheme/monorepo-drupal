document.addEventListener('DOMContentLoaded', () => {
  document.querySelector('.civic-button').addEventListener('click', () => {
    // eslint-disable-next-line no-console
    console.log('Triggered example click event for the link button');
  });
}, { once: true });
