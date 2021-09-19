document.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector('.civic-button')) {
    document.querySelector('.civic-button').addEventListener('click', () => {
      // eslint-disable-next-line no-console
      console.log('Triggered example click event for the button');
    });
  }
}, { once: true });
