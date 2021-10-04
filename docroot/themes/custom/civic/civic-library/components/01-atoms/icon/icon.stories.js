import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';

import CivicIcon from './icon.twig';
import CivicIconLink from './icon_link.twig';
import './icon.scss';
import './icon-link.scss';

export default {
  title: 'Atom/Icon',
  parameters: {
    layout: 'centered',
  },
};

export const Icon = () => {
  const { icons } = ICONS;
  const colors = [...new Set([...SCSS_VARIABLES['civic-default-colors'], ...SCSS_VARIABLES['civic-colors']])];
  const sizes = SCSS_VARIABLES['civic-icon-sizes'];

  return CivicIcon({
    symbol: select('Symbol', icons, icons[0]),
    color: select('Color', colors, 'primary'),
    size: radios('Size', sizes, sizes[0]),
  });
};

export const IconLibrary = () => {
  const { packs } = ICONS;
  const colors = [...new Set([...SCSS_VARIABLES['civic-default-colors'], ...SCSS_VARIABLES['civic-colors']])];
  const sizes = SCSS_VARIABLES['civic-icon-sizes'];

  const selectedPack = select('Pack', Object.keys(packs), Object.keys(packs).length ? Object.keys(packs)[0] : null);

  let html = ``;

  if (selectedPack) {
    html += `<h2>${selectedPack.charAt(0).toUpperCase()}${selectedPack.slice(1)}</h2>`;
    packs[selectedPack].forEach((icon) => {
      html += CivicIcon({
        symbol: icon,
        color: select('Color', colors, 'primary'),
        size: radios('Size', sizes, sizes[0]),
      });
    });

    html = `<div class="story-icon-wrapper story-wrapper-size--medium">${html}</div>`;
  }

  return html;
};

export const IconLink = () => {
  const { icons } = ICONS;
  const sizes = SCSS_VARIABLES['civic-icon-sizes'];
  const defaultIcon = icons.indexOf('brands_facebook');
  return CivicIconLink({
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
    ),
    symbol: select('Symbol', icons, defaultIcon !== -1 ? icons[defaultIcon] : icons[0]),
    size: radios('Size', sizes, sizes[0]),
    modifier_class: select(
      'Modifiers',
      {
        None: '',
        Visited: 'civic-icon-link--visited',
        Disabled: 'civic-icon-link--disabled',
      },
      '',
    ),
    text: text('Text', 'Link Text'),
    url: text('URL', 'http://example.com'),
    new_window: boolean('Open in a new window', false),
  });
};
