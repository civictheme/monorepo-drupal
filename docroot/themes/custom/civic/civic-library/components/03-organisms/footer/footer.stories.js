import CivicFooter from './footer.stories.twig'
import imageFile from '../../../assets/logo.png';
import './footer.scss';

export default {
  title: 'Organisms/Footer',
  component: CivicFooter,
}

export const Footer = CivicFooter.bind({});
Footer.args = {
  logo: imageFile
};
