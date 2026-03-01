// phpcs:ignoreFile
/**
 * CivicTheme Slider component.
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
  this.addSlideAriaAttributes();
  this.hideAllSlidesExceptCurrent();

  this.refresh();

  // Refresh slider on font-load.
  document.fonts.ready.then(() => {
    requestAnimationFrame(() => {
      this.refresh();
    });
  });
}

CivicThemeSlider.prototype.refresh = function () {
  // Set slide width based on panel width.
  const panelWidth = window.getComputedStyle(this.panel).width;
  const panelWidthVal = parseFloat(panelWidth);

  // Reset rail and panel height.
  this.rail.style.height = '';
  this.panel.style.height = '';

  // Set the rail width.
  this.rail.style.width = `${this.totalSlides * panelWidthVal}px`;

  // Reset slide heights.
  this.slides.forEach((slide) => {
    slide.style.height = null;
    slide.style.width = panelWidth;
  });

  // Show all slides temporarily to calculate heights.
  this.slides.forEach((slide) => slide.removeAttribute('data-slider-slide-hidden'));

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

CivicThemeSlider.prototype.addSlideAriaAttributes = function () {
  this.slides.forEach((slide, idx) => {
    slide.setAttribute('aria-label', `Slide ${idx + 1} of ${this.totalSlides}`);
  });
};

CivicThemeSlider.prototype.disableSlideInteraction = function () {
  this.rail.querySelectorAll('a, button').forEach((link) => {
    link.setAttribute('tabindex', '-1');
  });
};

CivicThemeSlider.prototype.hideAllSlidesExceptCurrent = function () {
  this.slides.forEach((slide, idx) => {
    if (idx !== this.currentSlide) {
      slide.setAttribute('data-slider-slide-hidden', 'true');
      slide.setAttribute('inert', true);
    } else {
      slide.removeAttribute('data-slider-slide-hidden');
      slide.removeAttribute('inert');
    }
  });
};

CivicThemeSlider.prototype.updateDisplaySlide = function () {
  const duration = parseFloat(window.getComputedStyle(this.rail).transitionDuration) * 1000;

  this.disableSlideInteraction();
  this.slides.forEach((slide) => slide.removeAttribute('data-slider-slide-hidden'));

  // Reset timer and wait for animation to complete.
  clearTimeout(this.animationTimeout);
  this.animationTimeout = setTimeout(() => {
    this.hideAllSlidesExceptCurrent();
    this.enableSlideInteraction();
  }, duration);
};

CivicThemeSlider.prototype.previousClick = function () {
  // Go to last slide if current slide is the first slide.
  if (this.currentSlide === 0) {
    this.currentSlide = this.totalSlides - 1;
  } else {
    this.currentSlide--;
  }
  this.rail.style.left = `${this.currentSlide * -100}%`;
  this.updateProgress();
  this.updateDisplaySlide();
};

CivicThemeSlider.prototype.nextClick = function () {
  // Go to first slide if current slide is the last slide.
  if (this.currentSlide === (this.totalSlides - 1)) {
    this.currentSlide = 0;
  } else {
    this.currentSlide++;
  }
  this.rail.style.left = `${this.currentSlide * -100}%`;
  this.updateProgress();
  this.updateDisplaySlide();
};

CivicThemeSlider.prototype.updateProgress = function () {
  this.progressIndicator.innerHTML = `Slide ${this.currentSlide + 1} of ${this.totalSlides}`;
};

document.querySelectorAll('[data-slider]').forEach((slider) => {
  new CivicThemeSlider(slider);
});
