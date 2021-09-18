import {text, select} from '@storybook/addon-knobs'

import Civicheading from './heading.twig'
import './heading.scss'

export default {
  title: 'Atom/Heading',
}

export const Heading = () => Civicheading({
  heading_level: select('Heading level',{
    'h1':'h1',
    'h2':'h2',
    'h3':'h3',
    'h4':'h4',
    'h5':'h5',
    'h6':'h6'
  }, 'h1'), 
  title: text('Text', 'Heading Text'),
})
