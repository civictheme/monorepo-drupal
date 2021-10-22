import { text, radios } from '@storybook/addon-knobs';

import CivicBasicContent from './basic-content.twig';
import './basic-content.scss';

export default {
  title: 'Molecule/Basic Content',
  parameters: {
    layout: 'fullscreen',
  },
};

export const BasicContent = () => CivicBasicContent({
  theme: radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
  ),
  content: text('Content', 'Filium morte multavit si sine causa, nollem me tamen laudandis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus vel elit laoreet, dignissim arcu sit amet, vulputate risus.'),
});
