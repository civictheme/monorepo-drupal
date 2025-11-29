// phpcs:ignoreFile
import CivicThemeAccordion from './accordion.twig';
import '../../00-base/collapsible/collapsible';
import { knobBoolean, knobNumber, knobRadios, knobText, randomSentence, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Molecules/Accordion',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Accordion = (parentKnobs = {}) => {
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
    expand_all: knobBoolean('Expand all', false, parentKnobs.expand_all, parentKnobs.knobTab),
    with_background: knobBoolean('With background', false, parentKnobs.with_background, parentKnobs.knobTab),
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
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  // Adding dynamic number of accordion panels.
  const panelsKnobTab = 'Panels';
  const numOfPanels = knobNumber(
    'Number of panels',
    3,
    {
      range: true,
      min: 0,
      max: 10,
      step: 1,
    },
    parentKnobs.number_of_panels,
    panelsKnobTab,
  );

  const panels = [];
  let itr = 1;
  while (itr <= numOfPanels) {
    panels.push({
      title: knobText(`Panel ${itr} title `, `Accordion title ${itr}`, parentKnobs[`panel_title_${itr}`], panelsKnobTab),
      content: `${knobText(`Panel ${itr} content`, randomSentence(100, 'accordion-panel'), parentKnobs[`panel_content_${itr}`], panelsKnobTab)} <a href="https://example.com">Example link</a>`,
      expanded: knobBoolean(`Panel ${itr} initially expanded`, knobs.expand_all, parentKnobs[`panel_expanded_${itr}`], panelsKnobTab),
    });
    itr += 1;
  }

  const combinedKnobs = { ...knobs, panels };

  return shouldRender(parentKnobs) ? CivicThemeAccordion({
    ...combinedKnobs,
    ...slotKnobs([
      'content_top',
      'content_bottom',
    ]),
  }) : combinedKnobs;
};
