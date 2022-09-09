// phpcs:ignoreFile
import { radios } from '@storybook/addon-knobs';
import CivicThemeBackToTop from './back-to-top.twig';

export default {
  title: 'Molecules/Back To Top',
  parameters: {
    layout: 'centered',
  },
};

export const BackToTop = (knobTab) => {
  const html = CivicThemeBackToTop();

  return `<a id="top"></a><div class="example-container"><div class="example-container__page-content example-civictheme-back-to-top"></div>${html}</div>`;
};
