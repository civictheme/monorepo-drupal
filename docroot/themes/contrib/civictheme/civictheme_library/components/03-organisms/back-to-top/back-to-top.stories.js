// phpcs:ignoreFile
import CivicThemeBackToTop from './back-to-top.twig';

export default {
  title: 'Organisms/Back To Top',
  parameters: {
    layout: 'centered',
  },
};

export const BackToTop = () => {
  const html = CivicThemeBackToTop();

  return `<a id="top"></a><div class="example-container"><div class="example-container__page-content example-ct-back-to-top"><div class="docs-container docs-container--large">Back To Top button appears when the bottom of the red rectangle reaches the top of the page.</div></div>${html}</div>`;
};
