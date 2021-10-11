import {
  boolean, radios, text, number,
} from '@storybook/addon-knobs';
import CivicAccordion from './accordion.twig';
// eslint-disable-next-line import/extensions
import './accordion.js';

export default {
  title: 'Molecule/Accordion',
  parameters: {
    layout: 'centered',
  },
};

export const Accordion = () => {
  const generalKnobTab = 'General';
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
  };

  const expandAll = boolean('Expand all', false, generalKnobTab);
  generalKnobs.expand_all = expandAll;

  // Adding dynamic number of accordion panels.
  const panelsKnobTab = 'Panels';
  const numOfPanels = number('Number of panels', 3, {}, panelsKnobTab);
  const panels = [];
  let itr = 1;
  while (itr <= numOfPanels) {
    panels.push({
      title: text(`Panel ${itr} title `, `Acccordion title ${itr}`, panelsKnobTab),
      content: text(`Panel ${itr} content`, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur harum magnam modi obcaecati vitae voluptatibus! Accusamus atque deleniti, distinctio esse facere, nam odio officiis omnis porro quibusdam quis repudiandae veritatis.', panelsKnobTab),
      expanded: boolean(`Panel ${itr} expanded`, expandAll, panelsKnobTab),
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
