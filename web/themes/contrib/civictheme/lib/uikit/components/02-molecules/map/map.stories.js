// phpcs:ignoreFile
import { knobBoolean, knobRadios, knobText, randomUrl, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';
import CivicThemeMap from './map.twig';

export default {
  title: 'Molecules/Map',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Map = (parentKnobs = {}) => {
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
    url: knobText('URL', 'https://maps.google.com/maps?q=australia&t=&z=3&ie=UTF8&iwloc=&output=embed', parentKnobs.url, parentKnobs.knobTab),
    address: knobText('Address', 'Australia', parentKnobs.address, parentKnobs.knobTab),
    view_url: knobText('View URL', randomUrl(), parentKnobs.view_url, parentKnobs.knobTab),
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

  return shouldRender(parentKnobs) ? CivicThemeMap({
    ...knobs,
    ...slotKnobs([
      'content_top',
      'content_bottom',
    ]),
  }) : knobs;
};
