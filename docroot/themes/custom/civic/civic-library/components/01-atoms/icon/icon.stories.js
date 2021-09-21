import { text, select } from '@storybook/addon-knobs'

import CivicIcon from './icon.twig'
import './icon.scss'

// Use the icons availabe in the assets directory to compile a list of spritesheets and icon IDs.
const spritesheets = []
const icons = {}
require.context('../../../assets/icons/', true, /\.svg$/).keys().forEach(path => {
  // Get a list of all spritesheets.
  const spritesheetName = path.substring(2, path.indexOf('/', 2)).replace(/\s/gi, '-').toLowerCase()
  const spritesheetURL = `/icons/civic-${spritesheetName}.svg`
  spritesheets.push(spritesheetURL)

  // Get all icons available within the spritesheets.
  const iconName = path.substring(path.lastIndexOf('/') + 1, path.lastIndexOf('.')).replace(/\s/gi, '-').toLowerCase()
  if (!icons[spritesheetURL]) {
    icons[spritesheetURL] = []
  }
  icons[spritesheetURL].push(`${spritesheetName}-${iconName}`)
})

export default {
  title: 'Atom/Icon',
}

export const Icon = () => {
  let spritesheet = select('Icon Pack', spritesheets, spritesheets[0])
  let symbol = select('Symbol', icons[spritesheet], icons[spritesheet][0])
  const colors = CIVIC_VARIABLES['civic-default-colors']

  return CivicIcon({
    spritesheet: spritesheet,
    symbol: symbol,
    color: select('Color', colors, 'primary'),
  })
}

export const IconLibrary = () => {
  return `<div class="icon-wrapper">
  <h2>Arrows</h2>
  <div>
    ${CivicIcon({ symbol: 'caret' })}
  </div>
</div>`
}
