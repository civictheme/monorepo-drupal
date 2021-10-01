import { select } from '@storybook/addon-knobs'

import CivicIcon from './icon.twig'
import './icon.scss'

export default {
  title: 'Atom/Icon',
  parameters: {
    layout: 'centered',
  },
}

export const Icon = () => {
  const icons = CIVIC_ICON.icons
  const colors = CIVIC_VARIABLES['civic-default-colors']

  return CivicIcon({
    symbol: select('Symbol', icons, icons[0]),
    color: select('Color', colors, 'primary'),
  })
}

export const IconLibrary = () => {
  const packs = CIVIC_ICON.packs
  const colors = CIVIC_VARIABLES['civic-default-colors']

  const selectedPack = select('Pack', Object.keys(packs), Object.keys(packs).length ? Object.keys(packs)[0] : null)
  const selectedColor = select('Color', colors, 'primary')

  let html = ``

  if (selectedPack) {
    html += '<h2>' + selectedPack.charAt(0).toUpperCase() + selectedPack.slice(1) + '</h2>'
    packs[selectedPack].forEach(icon => {
      html += CivicIcon({
        symbol: icon,
        color: selectedColor
      })
    })

    html = `<div class="icon-wrapper wrapper-size--medium">${html}</div>`;
  }

  return html;
}
