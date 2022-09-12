// phpcs:ignoreFile
import CivicThemeResponsive from './responsive.stories.twig';
import './responsive';
import '../collapsible/collapsible';

export default {
  title: 'Base/Responsive',
};

export const Responsive = () => {
  const html = CivicThemeResponsive();

  return `<div class="story-wrapper--centered-both story-wrapper-size--medium">${html}</div>`;
};
