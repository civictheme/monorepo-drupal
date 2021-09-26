import {radios, text, boolean} from '@storybook/addon-knobs'
import CivicPromoCard from "./promo-card.twig";
import imageFile from "../../../assets/image.png";

export default {
  title: 'Molecule/Promo Card'
}

let exampleSummary = 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.'

const imageAttr = {
  src: imageFile,
  alt: 'Civic image',
};

export const PromoCard = () => CivicPromoCard({
  theme: radios(
    'Theme',
    {
      'Light': 'light',
      'Dark': 'dark',
    },
    'light'
  ),
  title: text('Title', 'Promo Card title from knob'),
  summary: text('Summary', exampleSummary),
  url: text('Link URL', null),
  image: {
    src: text('Image path', imageAttr.src),
    alt: text('Image alt text', imageAttr.alt)
  },
  content_top_display: boolean('Display Content Top', false),
  date: text('Date', '1 Jun 1970'),
  topic: text('Topic', 'Topic name')
});
