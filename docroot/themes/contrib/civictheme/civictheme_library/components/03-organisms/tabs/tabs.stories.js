import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeTabs from './tabs.twig';
import './tabs';
import { randomText, randomUrl } from '../../00-base/base.stories';

export default {
  title: 'Organisms/Tabs',
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
    with_panels: boolean('With panels', true, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  let panelKnobs = {};
  let panelsKnobTab = '';

  if (generalKnobs.with_panels) {
    panelsKnobTab = 'Panels';
    // Adding dynamic number of tabs panels.
    const numOfPanels = number(
      'Number of panels',
      3,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      panelsKnobTab,
    );

    const panels = [];
    let itr = 1;
    while (itr <= numOfPanels) {
      panels.push({
        id: `tab-${itr}`,
        title: text(`Tab ${itr} title `, `Tab ${itr}`, panelsKnobTab),
        content: text(`Panel ${itr} content`, `Panel ${itr} content ${randomText()}`, panelsKnobTab),
        is_selected: boolean(`Selected ${itr} panel`, false, panelsKnobTab),
      });
      itr += 1;
    }

    panelKnobs = {
      panels,
    };
  } else {
    panelsKnobTab = 'Tabs';
    const numOfLinks = number(
      'Number of tabs',
      3,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      panelsKnobTab,
    );

    const links = [];
    let itr = 1;
    while (itr <= numOfLinks) {
      links.push({
        text: text(`Tab ${itr} title `, `Tab ${itr}`, panelsKnobTab),
        url: text(`Tab ${itr} URL`, randomUrl(), panelsKnobTab),
        modifier_class: itr === 1 ? 'selected' : '',
      });
      itr += 1;
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
