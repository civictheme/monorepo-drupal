/**
 * JS library example for 'Demo Button' component.
 *
 * Demonstration of attaching a library to a component: all clicks on the
 * demo button will result in showing JS alert.
 *
 * Note that there is no 'Drupal.behaviors' wrapper present - it is added
 * automatically by Webpack during FE assets build, which will produce
 * the following code in dist/scripts.js:
 *
 * @code
 * Drupal.behaviors.<'name' + random_number> = {
 *   attach: function (context, settings) {
 *     if (document.querySelector('[data-component-name="demo-button"]')) {
 *     document.querySelector('[data-component-name="demo-button"]').addEventListener('click', () => {
 *       // eslint-disable-next-line no-alert
 *       alert('Triggered example click event for the Demo Button');
 *     });
 *   }
 * }};
 * @endcode
 */
if (document.querySelector('[data-component-name="demo-button"]')) {
  document.querySelector('[data-component-name="demo-button"]').addEventListener('click', () => {
    // eslint-disable-next-line no-alert
    alert('Triggered example click event for the Demo Button');
  });
}
