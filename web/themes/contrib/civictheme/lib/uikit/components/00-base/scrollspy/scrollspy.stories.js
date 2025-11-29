// phpcs:ignoreFile
import './scrollspy';

export default {
  title: 'Base/Utilities/Scrollspy',
  parameters: {
    layout: 'fullscreen',
    docs: 'Scroll the viewport to see elements appear when it reaches a specific pixel threshold.',
  },
};

export const Scrollspy = () => `
  <div class="story-container">
    <div class="story-container__page-content story-scrollspy"></div>
    <button class="story-scrollspy-target1" data-scrollspy data-scrollspy-offset="400">
      This Button appears when the bottom of the red container reaches top when the viewport is scrolled 400px. It disappears when the viewport is scrolled back.
    </button>
    <button class="story-scrollspy-target2" data-scrollspy data-scrollspy-offset="600">
      This Button appears when the bottom of the blue container reaches top when the viewport is scrolled 600px. It disappears when the viewport is scrolled back.
    </button>
  </div>`;
