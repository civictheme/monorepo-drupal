import './base.stories.scss'

export default {
  title: 'Base/Colors',
}

export const Colors = () => {
  return `<div class="colors-wrapper">
  <div class="colors-group">
    <div class="color--primary"></div>
    <div class="color--primary-accent"></div>
    <div class="color--secondary"></div>
    <div class="color--secondary-accent"></div>
  </div>
  <div class="colors-group">
    <div class="color--shade_-90"></div>
    <div class="color--shade_15"></div>
    <div class="color--shade_30"></div>
    <div class="color--shade_45"></div>
    <div class="color--shade_60"></div>
  </div>
  <div class="colors-group">
    <div class="color--neutral_5"></div>
    <div class="color--neutral_10"></div>
    <div class="color--neutral_20"></div>
    <div class="color--neutral_40"></div>
    <div class="color--neutral_60"></div>
    <div class="color--neutral_80"></div>
    <div class="color--neutral_90"></div>
  </div>
  <div class="colors-group">
    <div class="color--heading"></div>
    <div class="color--heading-link"></div>
    <div class="color--body"></div>
    <div class="color--link"></div>
  </div>
  <div class="colors-group">
    <div class="color--information"></div>
    <div class="color--success"></div>
    <div class="color--warning"></div>
    <div class="color--error"></div>
  </div>
</div>`
}
