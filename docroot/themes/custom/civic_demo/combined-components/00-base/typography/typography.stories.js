import './typography.stories.scss'

export default {
  title: 'Base/Typography',
}

export const Typography = () => {
  return `<div class="typography-wrapper">
<hr/>
<div class="display-xl">XL - Desktop Display Text</div>
<div class="display-l">L - Tablet Display Text</div>
<div class="display-m">M - Phone Display Text</div>
<div class="display-s">S - Phone Display Text</div>
<div class="display-support">Support Display Text</div>
<hr/>
<div class="heading-1">Heading 1 desktop</div>
<div class="heading-2">Heading 2 desktop</div>
<div class="heading-3">Heading 3 desktop</div>
<div class="heading-4">Heading 4 desktop</div>
<div class="heading-5">Heading 5 desktop</div>
<div class="heading-6">Heading 6 desktop</div>
<hr/>
<div class="lead">Lead copy morbi scelerisque porttitor elit vel gravida. Duis id sollicitudin ante, in condimentum diam. Morbi faucibus egestas metus, et semper felis tempus at.</div>
<div class="body">Body copy proin tristique cursus ante in aliquam. Ut dictum lorem ut mauris venenatis, non consectetur massa feugiat. Aliquam quis lectus quam. Quisque quis tempor eros. Integer tempus odio velit, vel porta tellus viverra posuere. Sed id ante nec dui auctor mollis facilisis ut velit.</div>
<div class="small">Small copy fusce eget quam a quam blandit blandit lacinia id sapien. Sed commodo blandit urna nec tincidunt. Vestibulum et eros nisl. Maecenas consectetur ex tortor, a suscipit leo lacinia ac. Donec egestas, mauris vulputate tempus ullamcorper, augue magna semper erat, et vehicula eros lacus bibend.</div>
<hr/>
<div class="quote">“The RPL replaces the student pilot licence and general flying progress test (GFPT) that existed under the Civil Aviation Regulations 1988.”</div>
<hr/>
<ul class="unordered-list">
	<li>List item 1</li>
	<li>List item 2</li>
	<li>List item 3</li>
</ul>
<ol class="ordered-list">
	<li>List item 1</li>
	<li>List item 2</li>
	<li>List item 3</li>
</ol>
<hr/>
<div class="label-large">Large label</div>
<div class="label-regular">Normal label</div>
<div class="label-small">Small label</div>
</div>`
}
