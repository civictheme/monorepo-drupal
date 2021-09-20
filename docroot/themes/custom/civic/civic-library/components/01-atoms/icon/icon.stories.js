import { text, select } from '@storybook/addon-knobs'

const packMap = {}
const packs = []
const icons = {}
require.context('../../../assets/icons/', true, /\.svg$/).keys().forEach(path => {
  const pkName = path.substring(2, path.indexOf('/', 2)).replace(/\s/gim, '-').toLowerCase()
  const pkPath = `/icons/civic-${pkName}.svg`
  packs.push(pkPath)
  const relativePath = `/icons/${path.substr(2)}`
  const name = path.substring(2, path.lastIndexOf('.'))
  const n2 = path.substring(path.lastIndexOf('/') + 1, path.lastIndexOf('.')).replace(/\s/gim, '-').toLowerCase()
  if (!packMap[pkPath]) {
    packMap[pkPath] = []
  }
  packMap[pkPath].push(`${pkName}-${n2}`)
  icons[name] = relativePath
})

import CivicIcon from './icon.twig'
import './icon.scss'

export default {
  title: 'Atom/Icon',
}

export const Icon = () => {
  let iconPack = select('Icon Pack', packs, packs[0])
  let symbol = select('Symbol', packMap[iconPack], packMap[iconPack][0])
  return CivicIcon({
    old_symbol: select('Old Symbol (My first go)', icons, icons[Object.keys(icons)[0]]),
    pack: iconPack,
    symbol: symbol,
    color: select(
      'Color',
      {
        Primary: 'primary',
        Secondary: 'secondary',
        Accent: 'accent',
        White: 'white',
        'Heading general': 'heading-general',
        'Heading links': 'heading-links',
        Body: 'body',
        'Shade -90': 'shade_-90',
        'Shade 15': 'shade_15',
        'Shade 30': 'shade_30',
        'Shade 45': 'shade_45',
        'Shade 60': 'shade_60',
        'Neutral 5': 'neutral_5',
        'Neutral 10': 'neutral_10',
        'Neutral 20': 'neutral_20',
        'Neutral 40': 'neutral_40',
        'Neutral 60': 'neutral_60',
        'Neutral 80': 'neutral_80',
        'Neutral 90': 'neutral_90',
        Informtion: 'informtion',
        Success: 'success',
        Warning: 'warning',
        Error: 'error',
      },
      'primary',
    ),
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
