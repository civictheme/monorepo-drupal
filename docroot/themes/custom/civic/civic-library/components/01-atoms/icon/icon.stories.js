import { select } from '@storybook/addon-knobs'

import CivicIcon from './icon.twig'
import './icon.scss'

export default {
  title: 'Atom/Icon',
}

export const Icon = () => {
  const colors = CIVIC_VARIABLES['civic-default-colors']
  const icons = CIVIC_ICON.icons

  return CivicIcon({
    symbol: select('Symbol', icons, icons[0]),
    color: select('Color', colors, 'primary'),
  })
}

export const IconLibrary = () => {
  const colors = CIVIC_VARIABLES['civic-default-colors']
  const packs = CIVIC_ICON.packs
  const selectedColor = select('Color', colors, 'primary')
  let html = ``
  Object.keys(packs).forEach(key => {
    html += `<h2>${key}</h2>`
    packs[key].forEach(icon => {
      html += CivicIcon({
        symbol: icon,
        color: selectedColor
      })
    })
  })
  return `<div class="icon-wrapper">${html}</div>`
}
