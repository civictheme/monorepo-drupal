import {radios, text, boolean} from '@storybook/addon-knobs'
import CivicPromoCard from "./promo-card.twig";
import imageFile from "../../../assets/image.png";

export default {
  title: 'Molecule/Cards'
}

let exampleSummary = 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.'

export const PromoCard = () => {
  const imageAttr = {
    src: imageFile,
    alt: 'Civic image',
  };

  const promoCardParams = {
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
    date: text('Date', '1 Jun 1970'),
    topic: text('Topic', 'Topic name')
  }

  // Date format/
  let options = {
    year: "numeric",
    month: "short",
    day: "numeric"
  };

  promoCardParams.date = new Date(promoCardParams.date).toLocaleDateString("en-uk", options);

  return CivicPromoCard(promoCardParams);
}
