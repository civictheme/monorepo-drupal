import {text, boolean} from '@storybook/addon-knobs'

import CivicLink from './link.twig'
import './link.scss'

export default {
  title: 'Atom/Link',
}

export const Link = () => CivicLink({
  text: text('Text', 'Link Text'),
  href: text('URL', '/'),
  new_window: boolean('Open in a new window', false),
})
