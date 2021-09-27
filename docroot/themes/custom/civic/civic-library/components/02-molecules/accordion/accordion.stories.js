import CivicAccordion from './accordion.twig'
import './accordion.scss'
import './accordion.js'

export default {
  title: 'Atom/Accordion',
  component: CivicAccordion,
  argTypes: {
    theme: {
      name: "Theme",
      options: ['light', 'dark'],
      mappings: {
        'Light': 'light',
        'Dark': 'dark',
      },
      defaultValue: 'light',
      control: { type: 'radio' } // Automatically inferred when 'options' is defined
    },
  },
}

export const Accordion = (args) => CivicAccordion({
  ...args,
})
Accordion.args = {
  panels: [
    {
      "title": "Acccordion item 1",
      "content": "Accordion content",
      "expanded": false,
    },
    {
      "title": "Acccordion item 2",
      "content": "Accordion content",
      "expanded": true,
    },
  ],
  expand_all: false,
}
