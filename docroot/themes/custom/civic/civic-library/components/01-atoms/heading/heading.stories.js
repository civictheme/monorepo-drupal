import { text, radios } from '@storybook/addon-knobs';
import CivicHeading from './heading.twig';

export default {
  title: 'Atom/Heading',
};

export const Heading = () => CivicHeading({
  level: radios('Level', {
    H1: '1',
    H2: '2',
    H3: '3',
    H4: '4',
    H5: '5',
    H6: '6',
  }, '1'),
  title: text('Text', 'Heading Text'),
  modifier_class: text('Additional class', ''),
});
