import {
  boolean, radios, object,
} from '@storybook/addon-knobs';
import CivicBreadcrumb from './breadcrumb.twig';
import './breadcrumb.scss';

export default {
  title: 'Molecule/Breadcrumb',
};

export const Breadcrumb = () => {
  const generalKnobTab = 'General';

  return CivicBreadcrumb({
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    active_is_link: boolean('Active is link', false, generalKnobTab),
    links: object('Links', {
      link1: {
        text: 'Link 1',
        url: 'http://example.com',
      },
      link2: {
        text: 'Link 2 title',
        url: 'http://example.com',
      },
      link3: {
        text: 'Link 3 title',
        url: 'http://example.com',
      },
      link4: {
        text: 'Link 4 title',
        url: 'http://example.com',
      },
    }, generalKnobTab),
  });
};
