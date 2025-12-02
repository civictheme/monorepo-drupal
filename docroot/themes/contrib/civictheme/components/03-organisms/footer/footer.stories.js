import Component from './footer.stories.twig';
import LogoComponent from '../../02-molecules/logo/logo.twig';
import LogoData from '../../02-molecules/logo/logo.stories.data';
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved
import { getSlots } from '../../00-base/base.utils';
import '../../00-base/responsive/responsive';
import '../../00-base/collapsible/collapsible';

const meta = {
  title: 'Organisms/Footer',
  component: Component,
  render: (args) => {
    const logo = args.show_logo ? LogoComponent(LogoData.args(args.theme)) : null;

    const links1 = args.show_middle_links ? generateMenuLinks(4, 1, false) : null;
    const links2 = args.show_middle_links ? generateMenuLinks(4, 1, false) : null;
    const links3 = args.show_middle_links ? generateMenuLinks(4, 1, false) : null;
    const links4 = args.show_middle_links ? generateMenuLinks(4, 1, false) : null;

    return Component({
      theme: args.theme,
      logo,
      show_social_links: args.show_social_links,
      show_middle_links: args.show_middle_links,
      show_acknowledgement: args.show_acknowledgement,
      show_copyright: args.show_copyright,
      links1,
      links2,
      links3,
      links4,
      background_image: args.show_background_image ? Constants.BACKGROUNDS[args.background] : null,
      modifier_class: args.modifier_class,
      ...getSlots([
        'content_top1',
        'content_top2',
        'content_middle1',
        'content_middle2',
        'content_middle3',
        'content_middle4',
        'content_bottom1',
        'content_bottom2',
      ], args.show_slots),
    });
  },
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    show_logo: {
      control: { type: 'boolean' },
    },
    show_social_links: {
      control: { type: 'boolean' },
    },
    show_middle_links: {
      control: { type: 'boolean' },
    },
    show_acknowledgement: {
      control: { type: 'boolean' },
    },
    show_copyright: {
      control: { type: 'boolean' },
    },
    show_background_image: {
      control: { type: 'boolean' },
    },
    background: {
      control: { type: 'select' },
      options: Object.keys(Constants.BACKGROUNDS),
      if: { arg: 'show_background_image' },
    },
    show_slots: {
      control: { type: 'boolean' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Footer = {
  parameters: {
    layout: 'fullscreen',
  },
  args: {
    theme: 'light',
    show_logo: true,
    show_social_links: true,
    show_middle_links: true,
    show_acknowledgement: true,
    show_copyright: true,
    show_background_image: false,
    background: Object.keys(Constants.BACKGROUNDS)[0],
    show_slots: false,
    modifier_class: '',
  },
};
