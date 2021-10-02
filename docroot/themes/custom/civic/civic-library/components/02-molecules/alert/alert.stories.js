import { radios, text } from '@storybook/addon-knobs';
import CivicAlert from './alert.twig';

export default {
  title: 'Molecule/Messages',
};

export const Alert = () => CivicAlert({
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
      Info: 'info',
      Error: 'error',
      Warning: 'warning',
      Success: 'success',
    },
    'info',
  ),
  title: text('Alert title', 'The information on this page is currently being updated.'),
  description: text('Alert summary', 'Please contact your service desk for further assistance. Filium morte multavit si sine causa, nollem me tamen laudandis. Learn more. Please contact your service desk for further assistance. Filium morte multavit si sine causa, nollem me tamen laudandis. Learn more. Please contact your service desk for further assistance. Filium morte multavit si sine causa, nollem me tamen laudandis. Learn more.'),
});
