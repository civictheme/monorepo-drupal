/**
 * Slider component.
 */
function CivicSlider(el) {
  this.el = el;

  this.init();
}

CivicSlider.prototype.refresh = function () {
  // Set slide width based on panel width.
  const panelWidth = window.getComputedStyle(this.elSliderPanel).width;
  this.elSlides.forEach((slide) => {
    slide.style.width = panelWidth;
  });

  // Set the rail width.
  const panelWidthVal = parseFloat(panelWidth);
  this.elSliderRail.style.width = `${this.totalSlides * panelWidthVal}px`;

  // Reset slide heights.
  this.elSlides.forEach((slide) => {
    slide.style.height = null;
  });
  this.showAllSlides();

  // Set slide position and find largest slide.
  let largestHeight = 0;
  this.elSlides.forEach((slide, idx) => {
    slide.style.left = `${idx * panelWidthVal}px`;
    const slideHeight = slide.offsetHeight;
    if (slideHeight > largestHeight) {
      largestHeight = slideHeight;
    }
  });
  const largestHeightPx = `${largestHeight}px`;

  // Resize all slides to the largest slide.
  this.elSlides.forEach((slide) => {
    slide.style.height = largestHeightPx;
  });

  this.hideAllSlidesExceptCurrent();

  // Set heights based on largest slide height.
  this.elSliderRail.style.height = largestHeightPx;
  this.elSliderPanel.style.height = largestHeightPx;
};

CivicSlider.prototype.init = function () {
  // Get elements
  this.elSliderPanel = this.el.querySelector('[data-component-civictheme-slider-panel]');
  this.elSliderRail = this.el.querySelector('[data-component-civictheme-slider-rail]');
  this.elPrev = this.el.querySelector('[data-component-civictheme-slider-previous]');
  this.elNext = this.el.querySelector('[data-component-civictheme-slider-next]');
  this.elSlides = this.el.querySelectorAll('[data-component-civictheme-slider-slide]');
  this.elProgressIndicator = this.el.querySelector('[data-component-civictheme-slider-progress]');

  // Set events.
  this.previousClickEvent = this.previousClick.bind(this);
  this.nextClickEvent = this.nextClick.bind(this);
  this.refreshEvent = this.refresh.bind(this);

  this.elPrev.addEventListener('click', this.previousClickEvent);
  this.elNext.addEventListener('click', this.nextClickEvent);
  window.addEventListener('resize', this.refreshEvent);

  this.currentSlide = 0;
  this.totalSlides = this.elSlides.length;
  this.animationTimeout = null;
  this.updateProgress();
  this.updateButtonState();
  this.hideAllSlidesExceptCurrent();
  this.refresh();
};

CivicSlider.prototype.enableSlideInteraction = function () {
  this.elSliderRail.querySelectorAll('a').forEach((link) => {
    link.removeAttribute('tabindex');
  });
};

CivicSlider.prototype.disableSlideInteraction = function () {
  this.elSliderRail.querySelectorAll('a').forEach((link) => {
    link.setAttribute('tabindex', '-1');
  });
};

CivicSlider.prototype.showAllSlides = function () {
  this.elSlides.forEach((slide) => {
    slide.classList.remove('civictheme-slide--hidden');
  });
};

CivicSlider.prototype.hideAllSlidesExceptCurrent = function () {
  this.elSlides.forEach((slide, idx) => {
    if (idx !== this.currentSlide) {
      slide.classList.add('civictheme-slide--hidden');
    }
  });
};

CivicSlider.prototype.updateDisplaySlide = function () {
  const duration = parseFloat(window.getComputedStyle(this.elSliderRail).transitionDuration) * 1000;

  this.disableSlideInteraction();
  this.showAllSlides();

  // Reset timer and wait for animation to complete.
  clearTimeout(this.animationTimeout);
  this.animationTimeout = setTimeout(() => {
    this.hideAllSlidesExceptCurrent();
    this.enableSlideInteraction();
  }, duration);
};

CivicSlider.prototype.updateButtonState = function () {
  this.elPrev.disabled = (this.currentSlide === 0);
  if (this.elPrev.disabled) {
    this.elPrev.classList.remove('focus');
    this.elPrev.blur();
  }
  this.elNext.disabled = (this.currentSlide === (this.totalSlides - 1));
  if (this.elNext.disabled) {
    this.elNext.classList.remove('focus');
    this.elNext.blur();
  }
};

CivicSlider.prototype.previousClick = function () {
  this.currentSlide--;
  this.currentSlide = this.currentSlide < 0 ? 0 : this.currentSlide;
  this.elSliderRail.style.left = `${this.currentSlide * -100}%`;
  this.updateProgress();
  this.updateDisplaySlide();
  this.updateButtonState();
};

CivicSlider.prototype.nextClick = function () {
  this.currentSlide++;
  const total = this.totalSlides - 1;
  this.currentSlide = this.currentSlide > total ? total : this.currentSlide;
  this.elSliderRail.style.left = `${this.currentSlide * -100}%`;
  this.updateProgress();
  this.updateDisplaySlide();
  this.updateButtonState();
};

CivicSlider.prototype.updateProgress = function () {
  this.elProgressIndicator.innerHTML = `Slide ${this.currentSlide + 1} of ${this.totalSlides}`;
};

document.querySelectorAll('[data-component-civictheme-slider]').forEach((slider) => {
  new CivicSlider(slider);
});
