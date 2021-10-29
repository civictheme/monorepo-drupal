import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import CivicAccordion from './accordion.twig';
import './accordion';

export default {
  title: 'Organisms/Accordion',
  parameters: {
    layout: 'centered',
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
      content: text(`Panel ${itr} content`, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur harum magnam modi obcaecati vitae voluptatibus! Accusamus atque deleniti, distinctio esse facere, nam odio officiis omnis porro quibusdam quis repudiandae veritatis.', panelsKnobTab),
      expanded: boolean(`Panel ${itr} initially expanded`, generalKnobs.expand_all, panelsKnobTab),
    });
    itr += 1;
  }

  const panelKnobs = {
    panels,
  };

  const html = CivicAccordion({
    ...generalKnobs,
    ...panelKnobs,
  });

  return `<div class="story-wrapper-size--medium">${html}</div>`;
};
