import { text } from '@storybook/addon-knobs'

import CustomExampleDialog from './example-dialog.twig'

export default {
  title: 'Molecule/Example Dialog'
}

export const ExampleDialog = () => CustomExampleDialog({
  title: text('Title', 'Dialog title from knob'),
  text: text('Text', 'Dialog text from knob')
});
