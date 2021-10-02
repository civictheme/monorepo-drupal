import {boolean, date, radios, text} from "@storybook/addon-knobs";
import CivicContent from './content.stories.twig';
import imageFile from '../../../assets/image.png';
import './content.scss';

export default {
  title: 'Organisms/Content',
}

export const Content = () => {
  const generalKnobTab = 'General';

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        'Light': 'light',
        'Dark': 'dark'
      },
      'light',
      generalKnobTab
    ),
    date: date('Date', new Date(), generalKnobTab),
    title: text('Title', 'Title for inner component', generalKnobTab),
    summary: text('Summary', 'Summary using body copy which can run across multiple lines. Recommend limiting this summary to three or four lines..', generalKnobTab),
    url: text('Link URL', 'https://google.com', generalKnobTab),
    image: boolean('With image', true, generalKnobTab) ? {
      src: imageFile,
      alt: 'Image alt text',
    } : false,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  generalKnobs.date = new Date(generalKnobs.date).toLocaleDateString('en-uk', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });

  return CivicContent({
    ...generalKnobs,
  });
};
