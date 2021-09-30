import { select } from '@storybook/addon-knobs'

import CivicIcon from './icon.twig'
import './icon.scss'

// Use the icons available in the assets directory to compile a list of spritesheets and icon IDs.
const spritesheets = new Set()
const icons = {}
require.context('../../../assets/icons/', true, /\.svg$/).keys().forEach(path => {
  // Get a list of all spritesheets.
  const spritesheetName = path.substring(2, path.indexOf('/', 2)).replace(/\s/g, '-').toLowerCase()
  const spritesheetURL = `/icons/civic-${spritesheetName}.svg`
  spritesheets.add(spritesheetURL)

  // Get all icons available within the spritesheets.
  const iconName = path.substring(path.lastIndexOf('/') + 1, path.lastIndexOf('.')).toLowerCase().replace(/\s/g, '-').replace(/[^a-z0-9\-]+/, '')
  if (!icons[spritesheetURL]) {
    icons[spritesheetURL] = []
  }
  icons[spritesheetURL].push(`${spritesheetName}-${iconName}`)
})

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
