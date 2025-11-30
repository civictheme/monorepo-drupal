// phpcs:ignoreFile
import { knobBoolean, knobRadios, knobText, randomUrl, shouldRender } from '../../00-base/storybook/storybook.utils';
import CivicThemeLogo from './logo.twig';

export default {
  title: 'Molecules/Logo',
  parameters: {
    layout: 'centered',
  },
};

export const Logo = (parentKnobs = {}) => {
  const theme = knobRadios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    parentKnobs.theme,
    parentKnobs.knobTab,
  );

  const knobs = {
    theme,
    type: knobRadios(
      'Type',
      {
        Default: 'default',
        Stacked: 'stacked',
        Inline: 'inline',
        'Inline-Stacked': 'inline-stacked',
      },
      'default',
      parentKnobs.type,
      parentKnobs.knobTab,
    ),
    with_secondary_image: knobBoolean('With secondary image', false, parentKnobs.with_secondary_image, parentKnobs.knobTab),
    logos: {
      primary: {
        mobile: {
          url: LOGOS[theme].primary.mobile,
          alt: 'Primary logo mobile alt text',
        },
        desktop: {
          url: LOGOS[theme].primary.desktop,
          alt: 'Primary logo desktop alt text',
        },
      },
    },
    url: knobText('Link', randomUrl(), parentKnobs.url, parentKnobs.knobTab),
    title: knobText('Title', 'Logo title', parentKnobs.title, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
  };

  knobs.logos = knobs.with_secondary_image ? {
    ...knobs.logos,
    ...{
      secondary: {
        mobile: {
          url: LOGOS[theme].secondary.mobile,
          alt: 'Secondary logo mobile alt text',
        },
        desktop: {
          url: LOGOS[theme].secondary.desktop,
          alt: 'Secondary logo desktop alt text',
        },
      },
    },
  } : knobs.logos;

  return shouldRender(parentKnobs) ? CivicThemeLogo(knobs) : knobs;
};
