import {text, radios, boolean} from '@storybook/addon-knobs'

import CivicLinkButton from './link-button.twig'
import '..//button/button.scss'

export default {
  title: 'Atom/Link Button',
}

export const LinkButton = () => CivicLinkButton({
  modifier_class: [
    radios(
      'Type',
      {
        'Primary': 'civic-button--primary',
        'Primary Accent': 'civic-button--primary-accent',
        'Secondary': 'civic-button--secondary',
        'Secondary Accent': 'civic-button--secondary-accent',
      },
      'civic-button--primary',
    ),
    radios(
      'Size',
      {
        'Large': 'civic-button--large',
        'Normal': 'civic-button--normal',
        'Small': 'civic-button--small',
      },
      'civic-button--normal',
    )
  ].join(' '),
  text: text('Text', 'Link Button Text'),
  new_window: boolean('Open in a new window', false),
})
