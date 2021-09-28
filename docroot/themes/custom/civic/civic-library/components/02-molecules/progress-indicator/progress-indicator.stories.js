import {radios, select, text} from '@storybook/addon-knobs'
import CivicProgressIndicator from "./progress-indicator.twig";
import CivicIconSet from "../../00-base/icons.component.js";

export default {
  title: 'Molecule/ProgressIndicator'
}

//Knob tabs order is decided on the basis of their order in story.
//Icon component parameters.
const sheets = Array.from(CivicIconSet.spritesheets)
const icons = CivicIconSet.icons;
let spritesheet = select('Icon Pack', sheets, sheets[0]);
let iconpack = ['Icon Pack', sheets, sheets[0]];
let symbol = ['Symbol', icons[spritesheet], icons[spritesheet][0]];

let status = [
  'Status',
  {
    'Doing': 'doing',
    'To do': 'todo',
    'Done': 'done',
  },
]

export const ProgressIndicator = () => CivicProgressIndicator({
  theme: radios(
    'Theme',
    {
      'Light': 'light',
      'Dark': 'dark',
    },
    'light',
    'Theme',
  ),
  steps: [
    {
      title: text('Title', 'Step title 1', 'Step 1'),
      status: radios(...status, 'todo', 'Step 1'),
      spritesheet: select(...iconpack, 'Step 1'),
      symbol: select(...symbol, 'Step 1'),
    },
    {
      title: text('Title', 'Step title 2', 'Step 2'),
      status: radios(...status, 'doing', 'Step 2'),
      spritesheet: select(...iconpack, 'Step 2'),
      symbol: select(...symbol, 'Step 2'),
    },
    {
      title: text('Title', 'Step title 3', 'Step 3'),
      status: radios(...status, 'done', 'Step 3'),
      spritesheet: select(...iconpack, 'Step 3'),
      symbol: select(...symbol, 'Step 3'),
    },
  ]
});
