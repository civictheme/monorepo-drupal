import CivicFooter from './footer.twig'
import imageFile from '../../../assets/logo.png';
import './footer.scss';

export default {
  title: 'Organisms/footer',
  component: CivicFooter,
}

export const Footer = CivicFooter.bind({});
Footer.args = {
  logo: imageFile
};
