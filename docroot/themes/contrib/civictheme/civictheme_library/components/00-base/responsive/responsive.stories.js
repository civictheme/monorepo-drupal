// phpcs:disable Generic.PHP.UpperCaseConstant.Found
// phpcs:disable Squiz.WhiteSpace.OperatorSpacing
import CivicThemeResponsive from './responsive.stories.twig';
import './responsive';
import '../collapsible/collapsible';

export default {
  title: 'Base/Responsive',
};

export const Responsive = () => {
  const html = CivicThemeResponsive();

  return `<div class="story-wrapper-centered story-wrapper-size--medium">${html}</div>`;
};
