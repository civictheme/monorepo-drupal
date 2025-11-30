// phpcs:ignoreFile
import CivicThemeTabs from './tabs.twig';
import './tabs';
import { knobBoolean, knobNumber, knobRadios, knobText, placeholder, randomText, randomUrl, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Molecules/Tabs',
};

export const Tabs = (parentKnobs = {}) => {
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
    tabs_count: knobNumber(
      'Tabs count',
      3,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      parentKnobs.tabs_count,
      parentKnobs.knobTab,
    ),
    with_panels: knobBoolean('With panels', true, parentKnobs.with_panels, parentKnobs.knobTab),
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
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
    modifier_class: knobText('Additional classes', '', parentKnobs.modifier_class, parentKnobs.knobTab),
  };

  let panelKnobs = {};

  if (knobs.with_panels) {
    // Use panels.
    const panels = [];

    for (let i = 1; i <= knobs.tabs_count; i++) {
      panels.push({
        id: `tab-${i}`,
        title: `Panel ${i} title `,
        content: placeholder(`Panel ${i} content ${randomText()}`),
      });
    }

    panelKnobs = {
      panels,
    };
  } else {
    // Use tabs.
    const links = [];
    for (let i = 1; i <= knobs.tabs_count; i++) {
      links.push({
        text: `Tab ${i} title `,
        url: randomUrl(),
        modifier_class: i === 1 ? 'ct-tabs__tab--selected' : '',
      });
    }

    panelKnobs = {
      links,
    };
  }

  const combinedKnobs = { ...knobs, ...panelKnobs };

  return shouldRender(parentKnobs) ? CivicThemeTabs(combinedKnobs) : combinedKnobs;
};
