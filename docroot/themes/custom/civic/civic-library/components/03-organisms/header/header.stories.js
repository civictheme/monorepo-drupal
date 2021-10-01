import CivicHeader from './header.stories.twig'
import imageFile from '../../../assets/logo.png';
import './header.scss';

export default {
  title: 'Organisms/Header',
  component: CivicHeader,
}

export const Header = CivicHeader.bind({});
Header.args = {
  logo: imageFile,
};
