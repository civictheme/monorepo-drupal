const spritesheets = new Set()
const iconsComponent = {}
// Use the icons availabe in the assets directory to compile a list of spritesheets and icon IDs.
require.context('../../assets/icons/', true, /\.svg$/).keys().forEach(path => {
  // Get a list of all spritesheets.
  const spritesheetName = path.substring(2, path.indexOf('/', 2)).replace(/\s/g, '-').toLowerCase()
  const spritesheetURL = `/icons/civic-${spritesheetName}.svg`
  spritesheets.add(spritesheetURL)

  // Get all icons available within the spritesheets.
  const iconName = path.substring(path.lastIndexOf('/') + 1, path.lastIndexOf('.')).toLowerCase().replace(/\s/g, '-').replace(/[^a-z0-9\-]+/, '')
  if (!iconsComponent[spritesheetURL]) {
    iconsComponent[spritesheetURL] = []
  }
  iconsComponent[spritesheetURL].push(`${spritesheetName}-${iconName}`)
});

const CivicIconSet = {
  spritesheets: spritesheets,
  icons: iconsComponent,
}

export default CivicIconSet;
