// phpcs:ignoreFile
import './scrollspy';

export default {
  title: 'Base/Scrollspy',
};

export const Scrollspy = () => `
  <div class="example-container">
    <div class="example-container__page-content example-scrollspy"></div>
    <button class="example-scrollspy-target1" data-scrollspy data-scrollspy-offset="400">
      Button to scrollspy at 400px
    </button>
    <button class="example-scrollspy-target2" data-scrollspy data-scrollspy-offset="600">
      Button to scrollspy at 600px
    </button>
  </div>`;
