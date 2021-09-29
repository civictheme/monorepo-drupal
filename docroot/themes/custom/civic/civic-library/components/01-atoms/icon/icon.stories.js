import { select } from '@storybook/addon-knobs'

import CivicIcon from './icon.twig'
import './icon.scss'

// Use the icons available in the assets directory to compile a list of spritesheets and icon IDs.
const icons = []
require.context('../../../assets/icons/', true, /\.svg$/).keys().forEach(path => {
  const name = path.substring(2).replace('.svg', '').replace(/[\/\-]/g, '_').toLowerCase().replace(/[^a-z0-9\-_]+/g, '')
  icons.push(name)
})
const packs = {}
icons.forEach(icon => {
  const packName = icon.substring(0, icon.indexOf('_'))
  if (!packs[packName]) {
    packs[packName] = []
  }
  packs[packName].push(icon)
})

export default {
  title: 'Atom/Icon',
}

export const Icon = () => {
  let symbol = select('Symbol', icons, icons[0])
  const colors = CIVIC_VARIABLES['civic-default-colors']

  return CivicIcon({
    symbol: symbol,
    color: select('Color', colors, 'primary'),
  })
}

export const IconLibrary = () => {
  const colors = CIVIC_VARIABLES['civic-default-colors']
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
