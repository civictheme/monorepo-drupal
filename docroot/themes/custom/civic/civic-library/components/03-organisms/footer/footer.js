if (document.querySelector('[data-component-name="civic-footer"]')) {
  const menuHeaders = document.querySelectorAll('[data-component-name="civic-footer"] .civic-footer__menu-heading');
  for (const menuHeader of menuHeaders) {
    menuHeader.addEventListener('click', (e) => {
      const et = e.target;

      // select active class
      const active = document.querySelector('[data-component-name="civic-footer"] .open');

      // Check if current opened accordion is clicked
      const current = et.classList.contains('open');

      /* when a button is clicked, remove the active class from the button that has it */
      if (active) {
        active.classList.remove('open');
      }

      // Add active class to the clicked element
      if (!current) {
        et.classList.add('open');
      }
    });
  }
}
