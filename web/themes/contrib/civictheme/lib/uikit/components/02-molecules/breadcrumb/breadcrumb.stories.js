// phpcs:ignoreFile
import CivicThemeBreadcrumb from './breadcrumb.twig';
import { knobBoolean, knobNumber, knobRadios, knobText, randomLinks, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Molecules/Breadcrumb',
  parameters: {
    layout: 'centered',
  },
};

export const Breadcrumb = (parentKnobs = {}) => {
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
    active_is_link: knobBoolean('Active is a link', false, parentKnobs.active_is_link, parentKnobs.knobTab),
    links: randomLinks(knobNumber(
      'Count of links',
      3,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      parentKnobs.count_of_links,
      parentKnobs.knobTab,
    ), knobNumber(
      'Length of links',
      6,
      {
        range: true,
        min: 6,
        max: 100,
        step: 1,
      },
      parentKnobs.length_of_links,
      parentKnobs.knobTab,
    ) - 6),
    modifier_class: knobText('Additional classes', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeBreadcrumb(knobs) : knobs;
};
