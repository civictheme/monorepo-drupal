// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeTabs from './tabs.twig';
import './tabs';
import { placeholder, randomText, randomUrl } from '../../00-base/base.utils';

export default {
  title: 'Molecules/Tabs',
};

export const Tabs = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    // Dynamic number of tabs/panels.
    tabs_count: number(
      'Tabs count',
      3,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    ),
    with_panels: boolean('With panels', true, generalKnobTab),
    vertical_spacing: radios(
      'Vertical spacing',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      generalKnobTab,
    ),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
  };

  let panelKnobs = {};

  if (generalKnobs.with_panels) {
    // Use panels.
    const panels = [];

    for (let i = 1; i <= generalKnobs.tabs_count; i++) {
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
    for (let i = 1; i <= generalKnobs.tabs_count; i++) {
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

  return CivicThemeTabs({
    ...generalKnobs,
    ...panelKnobs,
  });
};
