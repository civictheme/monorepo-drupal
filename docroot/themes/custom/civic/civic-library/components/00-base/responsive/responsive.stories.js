import CivicResponsive from './responsive.stories.twig';
import './responsive';
import '../collapsible/collapsible';

export default {
  title: 'Base/Responsive',
};

export const Responsive = () => {
  const html = CivicResponsive();

  return `<div class="story-wrapper-centered story-wrapper-size--medium">${html}</div>`;
};
