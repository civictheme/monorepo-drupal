// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeTabs from './tabs.twig';
import './tabs';
import { randomText, randomUrl } from '../../00-base/base.stories';

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
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
  };

  let panelKnobs = {};
  let panelsKnobTab = '';

  if (generalKnobs.with_panels) {
    // Use panels.
    const panels = [];
    let itr = 1;
    while (itr <= generalKnobs.tabs_count) {
      panelsKnobTab = `Panel ${itr}`;
      panels.push({
        id: `tab-${itr}`,
        title: text(`Panel ${itr} title `, `Panel ${itr}`, panelsKnobTab),
        content: text(`Panel ${itr} content`, `Panel ${itr} content ${randomText()}`, panelsKnobTab),
        is_selected: boolean(`Selected ${itr} panel`, false, panelsKnobTab),
      });
      itr += 1;
    }

    panelKnobs = {
      panels,
    };
  } else {
    // Use tabs.
    const tabs = [];
    let itr = 1;
    while (itr <= generalKnobs.tabs_count) {
      panelsKnobTab =  `Tab ${itr}`;
      tabs.push({
        text: text(`Tab ${itr} title `, `Tab ${itr}`, panelsKnobTab),
        url: text(`Tab ${itr} URL`, randomUrl(), panelsKnobTab),
        modifier_class: itr === 1 ? 'selected' : '',
      });
      itr += 1;
    }

    panelKnobs = {
      tabs,
    };
  }

  return CivicThemeTabs({
    ...generalKnobs,
    ...panelKnobs,
  });
};
