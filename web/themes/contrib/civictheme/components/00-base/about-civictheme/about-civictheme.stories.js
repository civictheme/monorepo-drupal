// phpcs:ignoreFile
import AboutCivicThemeStoryTemplate from './about-civictheme.stories.twig';

export default {
  title: 'About CivicTheme',
  parameters: {
    layout: 'fullscreen',
    options: { showPanel: false },
    showPanel: false,
  },
};

export const AboutCivicTheme = () => AboutCivicThemeStoryTemplate({
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

AboutCivicTheme.storyName = 'About CivicTheme';
