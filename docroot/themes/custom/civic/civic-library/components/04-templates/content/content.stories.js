import CivicContent from './content.twig';
import './content.scss';
import imageFile from '../../../assets/image.png';

export default {
  title: 'Templates/Content',
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
  },
}

export const Content = CivicContent.bind({});
Content.args = {
  image: imageFile,
  theme: 'light',
};
