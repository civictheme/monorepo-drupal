// phpcs:ignoreFile
import CivicThemeSocialLinks from './social-links.twig';
import { demoImage, knobBoolean, knobRadios, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';
import CivicThemeIcon from '../../00-base/icon/icon.twig';

export default {
  title: 'Molecules/Social Links',
  parameters: {
    layout: 'centered',
  },
};

export const SocialLinks = (parentKnobs = {}) => {
  const items = [
    {
      title: 'Facebook',
      icon: 'facebook',
      url: 'https://www.facebook.com',
    },
    {
      title: 'Instagram',
      icon: 'instagram',
      url: 'https://www.instagram.com',
    },
    {
      title: 'Icon with inline SVG',
      // icon_html should take precedence.
      icon_html: CivicThemeIcon({
        symbol: 'linkedin',
        size: 'small',
      }),
      icon: 'linkedin',
      url: 'https://www.linkedin.com',
    },
    {
      title: 'X',
      icon: 'x',
      url: 'https://www.twitter.com',
    },
    {
      title: 'YouTube',
      icon: 'youtube',
      url: 'https://www.youtube.com',
    },
    {
      icon_html: `<img class="ct-button__icon" width="16" height="16" src="${demoImage(1)}"/>`,
      url: 'https://www.dropbox.com',
      // Deliberately left without a title.
    },
  ];

  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    items: knobBoolean('With items', true, parentKnobs.with_items, parentKnobs.knobTab) ? parentKnobs.items || items : null,
    with_border: knobBoolean('With border', true, parentKnobs.with_border, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeSocialLinks(knobs) : knobs;
};
