import CivicContent from './content.stories.twig';
import './content.scss';
import imageFile from '../../../assets/image.png';

export default {
  title: 'Organisms/Content',
  component: CivicContent,
  argTypes: {
    theme: {
      name: 'Theme',
      options: {
        'Light': 'light',
        'Dark': 'dark',
      },
      control: {type: 'radio'} // Automatically inferred when 'options' is defined
    },
    date: {
      name: 'Date',
      control: {type: 'date'} // Automatically inferred when 'options' is defined
    },
  },
}

export const Content = CivicContent.bind({});
Content.args = {
  theme: 'light',
  title: 'This is a test title for any content',
  summary: 'Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Donec rutrum congue leo eget malesuada. Sed porttitor lectus nibh. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.',
  url: 'http://google.com',
  image: {
    src: imageFile,
  },
  date: '1 Jun 1970',
};
