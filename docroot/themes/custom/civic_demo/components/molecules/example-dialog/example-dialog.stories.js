import { text, select, boolean } from '@storybook/addon-knobs'

import CustomExampleDialog from './example-dialog.twig'
import './example-dialog.css'

export default {
  title: 'Molecule/Example Dialog'
}

export const ExampleDialog = () => CustomExampleDialog({
  title: text('Title', 'Dialog title'),
  text: text('Text', 'Dialog text')
});
