import { text, select } from '@storybook/addon-knobs'
import { spritesheets, icons } from './icon-helper'

import CivicIcon from './icon.twig'
import './icon.scss'

export default {
  title: 'Atom/Icon',
}

export const Icon = () => {
  const sheets = Array.from(spritesheets)
  let spritesheet = select('Icon Pack', sheets, sheets[0])
  let symbol = select('Symbol', icons[spritesheet], icons[spritesheet][0])
  const colors = CIVIC_VARIABLES['civic-default-colors']

  return CivicIcon({
    spritesheet: spritesheet,
    symbol: symbol,
    color: select('Color', colors, 'primary'),
  })
}

export const IconLibrary = () => {
  let html = ``
  Array.from(spritesheets).forEach(sheet => {
    html += `<h2>${sheet.substring(sheet.lastIndexOf('/') + 1, sheet.lastIndexOf('.')).replace(/\-/g, ' ')}</h2>`
    icons[sheet].forEach(icon => {
      html += CivicIcon({
        spritesheet: sheet,
        symbol: icon
      })
    })
  })
  return `<div class="icon-wrapper">${html}</div>`
}
