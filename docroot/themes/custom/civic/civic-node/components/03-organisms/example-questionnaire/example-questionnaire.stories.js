import { text, select, boolean } from '@storybook/addon-knobs'

import CustomExampleQuestionnaire from './example-questionnaire.twig'

export default {
  title: 'Organism/Example Questionnaire'
}

export const ExampleQuestionnaire = () => CustomExampleQuestionnaire({
  title: text('Title', 'Questionnaire title from knob'),
  text: text('Text', 'Questionnaire text from knob'),
  dialog_text: text('Dialog text', 'Dialog text from questionnaire knob')
});
