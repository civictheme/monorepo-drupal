document.addEventListener('DOMContentLoaded', function   () {
  document.querySelector('.civic-button').addEventListener('click', function () {
    console.log('Triggered example click event for the button');
  });
}, {once: true});
