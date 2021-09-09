import { text, select } from '@storybook/addon-knobs'

import './global.stories.css'

export default {
  title: 'Atom/Global'
}

export const Spacing = () => {
  return `<div class="spacing-wrapper">
  <div class="spacing-8">
    <div class="spacing-7">
      <div class="spacing-6">
        <div class="spacing-5">
          <div class="spacing-4">
            <div class="spacing-3">
              <div class="spacing-2">
                <div class="spacing-1"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>`
}

export const Colours = () => {
  return `<div class="colors-wrapper">
  <div class="colors-group">
    <div class="color-primary"></div>
    <div class="color-secondary"></div>
    <div class="color-accent"></div>
    <div class="color-white"></div>
  </div>
  <div class="colors-group">
    <div class="color-heading-general"></div>
    <div class="color-heading-links"></div>
    <div class="color-body-copy"></div>
  </div>
  <div class="colors-group">
    <div class="color-shade_-90"></div>
    <div class="color-shade_15"></div>
    <div class="color-shade_30"></div>
    <div class="color-shade_45"></div>
    <div class="color-shade_60"></div>
  </div>
  <div class="colors-group">
    <div class="color-neutral_5"></div>
    <div class="color-neutral_10"></div>
    <div class="color-neutral_20"></div>
    <div class="color-neutral_40"></div>
    <div class="color-neutral_60"></div>
    <div class="color-neutral_80"></div>
    <div class="color-neutral_90"></div>
  </div>
  <div class="colors-group">
    <div class="color-informtion"></div>
    <div class="color-success"></div>
    <div class="color-warning"></div>
    <div class="color-error"></div>
  </div>
</div>`
}

export const Typography = () => {
  return `<div class="typography-wrapper">
<hr/>
<div class="display-xl">XL - Desktop Display Text</div>
<div class="display-xl">XL - Desktop Display Text</div>
<div class="display-l">L - Tablet Display Text</div>
<div class="display-l">L - Tablet Display Text</div>
<div class="display-m">M - Phone Display Text</div>
<div class="display-m">M - Phone Display Text</div>
<div class="display-s">S - Phone Display Text</div>
<div class="display-s">S - Phone Display Text</div>
<hr/>
<div class="heading-1">Heading 1 desktop</div>
<div class="heading-2">Heading 2 desktop</div>
<div class="heading-3">Heading 3 desktop</div>
<div class="heading-4">Heading 4 desktop</div>
<div class="heading-5">Heading 5 desktop</div>
<div class="heading-6">Heading 6 desktop</div>
<hr/>
</div>`
}
