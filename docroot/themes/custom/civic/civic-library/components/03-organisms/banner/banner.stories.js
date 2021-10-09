import {
  text, boolean, radios,
} from '@storybook/addon-knobs';
import { getSlots } from '../../00-base/base.stories';
import CivicBannerExample from './banner.stories.twig';
import CivicSearch from './search.twig';
import './banner.stories.scss';
import bgImage from '../../../assets/banner-background.png';

export default {
  title: 'Organisms/Banner',
  parameters: {
    layout: 'fullscreen',
  },
};

export const BannerExample = () => {
  const generalKnobTab = 'General';

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'dark',
      generalKnobTab,
    ),
    backgroundImage: text('Background image', bgImage, generalKnobTab),
    decorative: boolean('Decorative', true, generalKnobTab),
    showTopContent: boolean('Show example content for Top Content', false, generalKnobTab),
    showBreadcrumbs: boolean('Show example content for Breadcrumbs ', true, generalKnobTab),
    showControls: boolean('Show example content for Controls', false, generalKnobTab),
    showTopContent3: boolean('Show example content for Top Content 3', true, generalKnobTab),
    showBannerTitle: boolean('Show example content for Banner Title', true, generalKnobTab),
    showContentMiddle: boolean('Show example content for Content Middle', false, generalKnobTab),
    showContent: boolean('Show example content for Content', true, generalKnobTab),
    showBelowContent: boolean('Show example content for Below Content', false, generalKnobTab),
    modifier_class: 'civic-banner-example',
  };

  return CivicBannerExample({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'breadcrumbs',
      'content_top2',
      'content_top3',
      'content_title',
      'content_middle',
      'content',
      'below_content',
    ]),
  });
};

export const Search = () => {
  const generalKnobTab = 'General';

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    placeholder: text('Placeholder', 'Enter keywords or phrase', generalKnobTab),
    button_text: text('Button text', 'Search', generalKnobTab),
    description: text('Description', 'Search by keyword', generalKnobTab),
  };

  return CivicSearch({
    ...generalKnobs,
  });
};
