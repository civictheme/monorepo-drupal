/**
 * Demo button library.
 *
 * Used to demonstrate attaching a library to a component.
 *
 * Note - the use of data-attributes to attach to the component rather than
 * classes.
 *
 * This JS library is wrapped by Drupal.behaviours on build via webpack
 * so the resulting code is made available to the Drupal theme:
 *
 * @code
 * Drupal.behaviors.<'name' + random_number> = {
 * attach: function (context, settings) {
 *   if (document.querySelector('[data-component-name="demo-button"]')) {
 *   document.querySelector('[data-component-name="demo-button"]').addEventListener('click', () => {
 *     // eslint-disable-next-line no-alert
 *     alert('Triggered example click event for the Demo Button');
 *   });
 * }
 * }};
 * @endcode
 */
if (document.querySelector('[data-component-name="demo-button"]')) {
  document.querySelector('[data-component-name="demo-button"]').addEventListener('click', () => {
    // eslint-disable-next-line no-alert
    alert('Triggered example click event for the Demo Button');
  });
}
