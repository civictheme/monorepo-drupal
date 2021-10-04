import CivicAttachment from './attachment.twig';
import imageFile from '../../../assets/image.png';

export default {
  title: 'Molecule/Attachment',
  component: CivicAttachment,
  argTypes: {
    theme: {
      name: 'Theme',
      options: {
        Light: 'light',
        Dark: 'dark',
      },
      control: { type: 'radio' }, // Automatically inferred when 'options' is defined
    },
  },
};

const defaultArgs = {
  theme: 'light',
  title: 'Attachment card title test',
  summary: 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.',
  content_bottom: 'This is content bottom text',
};

export const Attachment = CivicAttachment.bind({});
Attachment.args = {
  ...defaultArgs,
  links: [
    {
      url: 'http://example.com',
      title: 'Test link title',
      last_updated: new Date().toLocaleDateString(),
    },
    {
      url: 'http://example.com',
      title: 'Test link title',
      last_updated: new Date().toLocaleDateString(),
    },
    {
      url: 'http://example.com',
      title: 'Test link title',
      last_updated: new Date().toLocaleDateString(),
    },
  ],
};

export const Publication = CivicAttachment.bind({});
Publication.args = {
  ...defaultArgs,
  icon: '/icons/civic-arrows.svg#arrows-bottom-alignment',
  link: {
    url: 'http://example.com',
    title: 'Test link title',
  },
  image: {
    src: imageFile,
    alt: 'Civic image alt',
  },
};
