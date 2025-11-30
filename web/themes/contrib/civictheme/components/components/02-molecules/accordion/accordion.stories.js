// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeAccordion from './accordion.twig';

import '../../00-base/collapsible/collapsible';
import { getSlots } from '../../00-base/base.utils';

export default {
  title: 'Molecules/Accordion',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Accordion = (knobTab) => {
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
    expand_all: boolean('Expand all', false, generalKnobTab),
    with_background: boolean('With background', false, generalKnobTab),
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
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  // Adding dynamic number of accordion panels.
  const panelsKnobTab = 'Panels';
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
      title: text(`Panel ${itr} title `, `Accordion title ${itr}`, panelsKnobTab),
      content: `${text(`Panel ${itr} content`, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur harum magnam modi obcaecati vitae voluptatibus! Accusamus atque deleniti, distinctio esse facere, nam odio officiis omnis porro quibusdam quis repudiandae veritatis.', panelsKnobTab)} <a href="https://example.com">Example link</a>`,
      expanded: boolean(`Panel ${itr} initially expanded`, generalKnobs.expand_all, panelsKnobTab),
    });
    itr += 1;
  }

  const panelKnobs = {
    panels,
  };

  return CivicThemeAccordion({
    ...generalKnobs,
    ...panelKnobs,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
