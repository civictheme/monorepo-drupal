import {text, select} from '@storybook/addon-knobs'

import Civicheading from './heading.twig'
import './heading.scss'

export default {
  title: 'Atom/Heading',
}

export const Heading = () => Civicheading({
  level: select('Heading level',{
    '1':'1',
    '2':'2',
    '3':'3',
    '4':'4',
    '5':'5',
    '6':'6'
  }, '1'), 
  title: text('Text', 'Heading Text'),
})
