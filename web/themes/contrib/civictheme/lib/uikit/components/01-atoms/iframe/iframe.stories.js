// phpcs:ignoreFile
import CivicThemeIframe from './iframe.twig';
import { knobBoolean, knobRadios, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Iframe',
  parameters: {
    layout: 'centered',
  },
};

export const Iframe = (parentKnobs = {}) => {
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
    url: knobText('URL', 'https://www.openstreetmap.org/export/embed.html?bbox=144.1910129785538%2C-38.33563928918572%2C146.0037571191788%2C-37.37170047141492&amp;layer=mapnik', parentKnobs.url, parentKnobs.knobTab),
    width: knobText('Width', '500', parentKnobs.width, parentKnobs.knobTab),
    height: knobText('Height', '300', parentKnobs.height, parentKnobs.knobTab),
    vertical_spacing: knobRadios(
      'Vertical spacing',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      parentKnobs.vertical_spacing,
      parentKnobs.knobTab,
    ),
    with_background: knobBoolean('With background', false, parentKnobs.with_background, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeIframe(knobs) : knobs;
};
