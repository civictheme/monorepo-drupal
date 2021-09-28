import './grid.stories.scss'

export default {
  title: 'Base/Grid',
}

export const Grid = () => {
  return `
  <h2>Columns</h2>
  <div class="grid-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-xs-1"><span>1</span></div>
        <div class="col-xs-1"><span>1</span></div>
        <div class="col-xs-1"><span>1</span></div>
        <div class="col-xs-1"><span>1</span></div>
        <div class="col-xs-1"><span>1</span></div>
        <div class="col-xs-1"><span>1</span></div>
        <div class="col-xs-1"><span>1</span></div>
        <div class="col-xs-1"><span>1</span></div>
        <div class="col-xs-1"><span>1</span></div>
        <div class="col-xs-1"><span>1</span></div>
        <div class="col-xs-1"><span>1</span></div>
        <div class="col-xs-1"><span>1</span></div>
      </div>
      <div class="row">
        <div class="col-xs-2"><span>2</span></div>
        <div class="col-xs-2"><span>2</span></div>
        <div class="col-xs-2"><span>2</span></div>
        <div class="col-xs-2"><span>2</span></div>
        <div class="col-xs-2"><span>2</span></div>
        <div class="col-xs-2"><span>2</span></div>
      </div>
      <div class="row">
        <div class="col-xs-3"><span>3</span></div>
        <div class="col-xs-3"><span>3</span></div>
        <div class="col-xs-3"><span>3</span></div>
        <div class="col-xs-3"><span>3</span></div>
      </div>
      <div class="row">
        <div class="col-xs-4"><span>4</span></div>
        <div class="col-xs-4"><span>4</span></div>
        <div class="col-xs-4"><span>4</span></div>
      </div>
      <div class="row">
        <div class="col-xs-6"><span>6</span></div>
        <div class="col-xs-6"><span>6</span></div>
      </div>
    </div>
  </div>

  <h2>Responsive</h2>
  <div class="grid-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-s-6 col-m-5 col-l-4 col-xl-3 col-xxl-2"><span>Column</span></div>
        <div class="col-xs-12 col-s-6 col-m-7 col-l-8 col-xl-9 col-xxl-10"><span>Column</span></div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-s-4 col-m-3 col-l-2 col-xl-4"><span>Column</span></div>
        <div class="col-xs-12 col-s-4 col-m-6 col-l-8 col-xl-4"><span>Column</span></div>
        <div class="col-xs-12 col-s-4 col-m-3 col-l-2 col-xl-4"><span>Column</span></div>
      </div>
    </div>
  </div>
</div>`
}
