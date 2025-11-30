// phpcs:ignoreFile
/**
 * Slider component.
 */
function CivicThemeSlider(el) {
  if (el.getAttribute('data-slider') === 'true' || this.el) {
    return;
  }

  this.el = el;

  this.panel = this.el.querySelector('[data-slider-panel]');
  this.rail = this.el.querySelector('[data-slider-rail]');
  this.prev = this.el.querySelector('[data-slider-previous]');
  this.next = this.el.querySelector('[data-slider-next]');
  this.slides = this.el.querySelectorAll('[data-slider-slide]');
  this.progressIndicator = this.el.querySelector('[data-slider-progress]');

  this.prev.addEventListener('click', this.previousClick.bind(this));
  this.next.addEventListener('click', this.nextClick.bind(this));
  window.addEventListener('resize', this.refresh.bind(this));

  this.currentSlide = 0;
  this.totalSlides = this.slides.length;
  this.animationTimeout = null;

  this.updateProgress();
  this.updateControlsState();
  this.hideAllSlidesExceptCurrent();

  this.refresh();
}

CivicThemeSlider.prototype.refresh = function () {
  // Set slide width based on panel width.
  const panelWidth = window.getComputedStyle(this.panel).width;
  this.slides.forEach((slide) => {
    slide.style.width = panelWidth;
  });

  // Set the rail width.
  const panelWidthVal = parseFloat(panelWidth);
  this.rail.style.width = `${this.totalSlides * panelWidthVal}px`;

  // Reset slide heights.
  this.slides.forEach((slide) => {
    slide.style.height = null;
  });
  this.showAllSlides();

  // Set slide position and find largest slide.
  let largestHeight = 0;
  this.slides.forEach((slide, idx) => {
    slide.style.left = `${idx * panelWidthVal}px`;
    const slideHeight = slide.offsetHeight;
    if (slideHeight > largestHeight) {
      largestHeight = slideHeight;
    }
  });
  const largestHeightPx = `${largestHeight}px`;

  // Resize all slides to the largest slide.
  this.slides.forEach((slide) => {
    slide.style.height = largestHeightPx;
  });

  this.hideAllSlidesExceptCurrent();

  // Set heights based on largest slide height.
  this.rail.style.height = largestHeightPx;
  this.panel.style.height = largestHeightPx;
};

CivicThemeSlider.prototype.enableSlideInteraction = function () {
  this.rail.querySelectorAll('a, button').forEach((link) => {
    link.removeAttribute('tabindex');
  });
};

CivicThemeSlider.prototype.disableSlideInteraction = function () {
  this.rail.querySelectorAll('a, button').forEach((link) => {
    link.setAttribute('tabindex', '-1');
  });
};

CivicThemeSlider.prototype.showAllSlides = function () {
  this.slides.forEach((slide) => {
    slide.setAttribute('data-slider-slide-hidden', true);
  });
};

CivicThemeSlider.prototype.hideAllSlidesExceptCurrent = function () {
  this.slides.forEach((slide, idx) => {
    if (idx !== this.currentSlide) {
      slide.removeAttribute('data-slider-slide-hidden');
    }
  });
};

CivicThemeSlider.prototype.updateDisplaySlide = function () {
  const duration = parseFloat(window.getComputedStyle(this.rail).transitionDuration) * 1000;

  this.disableSlideInteraction();
  this.showAllSlides();

  // Reset timer and wait for animation to complete.
  clearTimeout(this.animationTimeout);
  this.animationTimeout = setTimeout(() => {
    this.hideAllSlidesExceptCurrent();
    this.enableSlideInteraction();
  }, duration);
};

CivicThemeSlider.prototype.updateControlsState = function () {
  this.prev.disabled = (this.currentSlide === 0);
  if (this.prev.disabled) {
    this.prev.classList.remove('focus');
    this.prev.blur();
  }
  this.next.disabled = (this.currentSlide === (this.totalSlides - 1));
  if (this.next.disabled) {
    this.next.classList.remove('focus');
    this.next.blur();
  }
};

CivicThemeSlider.prototype.previousClick = function () {
  this.currentSlide--;
  this.currentSlide = this.currentSlide < 0 ? 0 : this.currentSlide;
  this.rail.style.left = `${this.currentSlide * -100}%`;
  this.updateProgress();
  this.updateDisplaySlide();
  this.updateControlsState();
};

CivicThemeSlider.prototype.nextClick = function () {
  this.currentSlide++;
  const total = this.totalSlides - 1;
  this.currentSlide = this.currentSlide > total ? total : this.currentSlide;
  this.rail.style.left = `${this.currentSlide * -100}%`;
  this.updateProgress();
  this.updateDisplaySlide();
  this.updateControlsState();
};

CivicThemeSlider.prototype.updateProgress = function () {
  this.progressIndicator.innerHTML = `Slide ${this.currentSlide + 1} of ${this.totalSlides}`;
};

document.querySelectorAll('[data-slider]').forEach((slider) => {
  new CivicThemeSlider(slider);
});
