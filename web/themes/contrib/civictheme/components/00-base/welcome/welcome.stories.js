// phpcs:ignoreFile
import './welcome.stories.scss';
import WelcomeStoryTemplate from './welcome.stories.twig';

export default {
  title: 'Welcome',
  parameters: {
    layout: 'fullscreen',
    options: { showPanel: false },
    showPanel: false,
  },
};

export const Welcome = () => WelcomeStoryTemplate({
  logos: {
    primary: {
      mobile: {
        url: LOGOS.light.civictheme.mobile,
      },
      desktop: {
        url: LOGOS.light.civictheme.desktop,
      },
    },
  },
});
