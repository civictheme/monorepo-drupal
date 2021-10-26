import { radios, text } from '@storybook/addon-knobs';
import CivicMessage from './message.twig';

export default {
  title: 'Organisms/Message',
};

export const Message = () => CivicMessage({
  theme: radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
  ),
  type: radios(
    'Type',
    {
      Status: 'status',
      Error: 'error',
      Warning: 'warning',
      Success: 'success',
    },
    'status',
  ),
  title: text('Title', 'The information on this page is currently being updated.'),
  description: text('Summary', 'Filium morte multavit si sine causa, nollem me tamen laudandis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus vel elit laoreet, dignissim arcu sit amet, vulputate risus.'),
  modifier_class: text('Additional class', ''),
});
