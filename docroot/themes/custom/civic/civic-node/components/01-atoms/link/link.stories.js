import { text } from '@storybook/addon-knobs'

import CTLink from './link.twig'
import './link.css'

export default {
  title: 'Atom/Link',
}

export const Link = () =>
  CTLink({
    text: text('Text', 'Link Text'),
    href: text('URL', '/'),
  })
