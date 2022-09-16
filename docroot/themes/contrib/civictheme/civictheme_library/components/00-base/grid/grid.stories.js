// phpcs:ignoreFile
export default {
  title: 'Base/Grid',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Grid = () => `
  <div class="example-container">
    <div class="example-container__title">Columns</div>
    <div class="story-grid-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-xxs-1"><span>1</span></div>
          <div class="col-xxs-1"><span>1</span></div>
          <div class="col-xxs-1"><span>1</span></div>
          <div class="col-xxs-1"><span>1</span></div>
          <div class="col-xxs-1"><span>1</span></div>
          <div class="col-xxs-1"><span>1</span></div>
          <div class="col-xxs-1"><span>1</span></div>
          <div class="col-xxs-1"><span>1</span></div>
          <div class="col-xxs-1"><span>1</span></div>
          <div class="col-xxs-1"><span>1</span></div>
          <div class="col-xxs-1"><span>1</span></div>
          <div class="col-xxs-1"><span>1</span></div>
        </div>
        <div class="row">
          <div class="col-xxs-2"><span>2</span></div>
          <div class="col-xxs-2"><span>2</span></div>
          <div class="col-xxs-2"><span>2</span></div>
          <div class="col-xxs-2"><span>2</span></div>
          <div class="col-xxs-2"><span>2</span></div>
          <div class="col-xxs-2"><span>2</span></div>
        </div>
        <div class="row">
          <div class="col-xxs-3"><span>3</span></div>
          <div class="col-xxs-3"><span>3</span></div>
          <div class="col-xxs-3"><span>3</span></div>
          <div class="col-xxs-3"><span>3</span></div>
        </div>
        <div class="row">
          <div class="col-xxs-4"><span>4</span></div>
          <div class="col-xxs-4"><span>4</span></div>
          <div class="col-xxs-4"><span>4</span></div>
        </div>
        <div class="row">
          <div class="col-xxs-6"><span>6</span></div>
          <div class="col-xxs-6"><span>6</span></div>
        </div>
      </div>
    </div>
  </div>

  <div class="example-container">
    <div class="example-container__title">Responsive</div>
    <div class="story-grid-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-xxs-12 col-s-6 col-m-5 col-l-4 col-xl-3 col-xxl-2"><span>Column</span></div>
          <div class="col-xxs-12 col-s-6 col-m-7 col-l-8 col-xl-9 col-xxl-10"><span>Column</span></div>
        </div>
        <div class="row">
          <div class="col-xxs-12 col-s-4 col-m-3 col-l-2 col-xl-4"><span>Column</span></div>
          <div class="col-xxs-12 col-s-4 col-m-6 col-l-8 col-xl-4"><span>Column</span></div>
          <div class="col-xxs-12 col-s-4 col-m-3 col-l-2 col-xl-4"><span>Column</span></div>
        </div>
      </div>
    </div>
  </div>

  <div class="example-container">
    <div class="example-container__title">Grid in a grid</div>
    <div class="story-grid-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-xxs-12 col-s-8">
            <div class="row">
              <div class="col-xxs-12 col-s-4"><span>Column in nested grid</span></div>
              <div class="col-xxs-12 col-s-4"><span>Column in nested grid</span></div>
              <div class="col-xxs-12 col-s-4"><span>Column in nested grid</span></div>
            </div>
          </div>
          <div class="col-xxs-12 col-s-4">
            <span>Column in parent grid</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="example-container">
    <div class="example-container__title">Auto Column</div>
    <div class="story-grid-wrapper">
      <div class="container">
        <div class="row">
          <div class="col"><span>A</span></div>
          <div class="col"><span>B</span></div>
          <div class="col"><span>C</span></div>
          <div class="col"><span>D</span></div>
        </div>
      </div>
    </div>
  </div>

  <div class="example-container">
    <div class="example-container__title">Reverse Row</div>
    <div class="story-grid-wrapper">
      <div class="container">
        <div class="row reverse">
        <div class="col"><span>A</span></div>
        <div class="col"><span>B</span></div>
        <div class="col"><span>C</span></div>
        <div class="col"><span>D</span></div>
        </div>
      </div>
    </div>
  </div>
</div>`;
