function CivicPasswordIndicator(el) {
  // Ensure element hasn't already been processed.
  if (this.el.getAttribute('data-password-indicator' === 'true' || this.el)) {
    return;
  }

  this.el = el;

  // Get settings.
  this.settings = {};

  // Text messages to display.
  this.settings.tooShort = this.el.getAttribute('data-password-indicator-setting-short-text') || 'Your password is too short';
  this.settings.addLowerCase = this.el.getAttribute('data-password-indicator-setting-lower-text') || 'Your password needs lowercase letters';
  this.settings.addUpperCase = this.el.getAttribute('data-password-indicator-setting-upper-text') || 'Your password needs uppercase letters';
  this.settings.addNumbers = this.el.getAttribute('data-password-indicator-setting-number-text') || 'Your password needs numbers';
  this.settings.addPunctuation = this.el.getAttribute('data-password-indicator-setting-punctuation-text') || 'Your password needs punctuation';
  this.settings.sameAsUsername = this.el.getAttribute('data-password-indicator-setting-username-text') || 'Your password is the same as your username';
  this.settings.weak = this.el.getAttribute('data-password-indicator-setting-weak-text') || 'Your password is very weak';
  this.settings.fair = this.el.getAttribute('data-password-indicator-setting-fair-text') || 'Your password is so-so';
  this.settings.good = this.el.getAttribute('data-password-indicator-setting-good-text') || 'Your password is good';
  this.settings.strong = this.el.getAttribute('data-password-indicator-setting-great-text') || 'Your password is great';
  this.settings.hasWeaknesses = this.el.getAttribute('data-password-indicator-setting-weakness-text') || 'Your password has weaknesses';

  // Checks to perform.
  this.settings.checkSize = !(this.el.getAttribute('data-password-indicator-setting-short') === 'false');
  this.settings.checkLower = !(this.el.getAttribute('data-password-indicator-setting-lower') === 'false');
  this.settings.checkUpper = !(this.el.getAttribute('data-password-indicator-setting-upper') === 'false');
  this.settings.checkNumber = !(this.el.getAttribute('data-password-indicator-setting-number') === 'false');
  this.settings.checkPunctuation = !(this.el.getAttribute('data-password-indicator-setting-punctuation') === 'false');
  this.settings.checkUsername = !(this.el.getAttribute('data-password-indicator-setting-username') === 'false');
  this.settings.checkMinSize = this.el.getAttribute('data-password-indicator-setting-min-count') || 12;

  const themeClass = Array.from(this.el.classList).filter((item) => (item.indexOf('civic-theme') === 0)).pop();

  const group = el.getAttribute('data-password-indicator-group');
  this.elUsername = document.querySelector(`[data-password-indicator-username][data-password-indicator-group="${group}"]`);
  this.elConfirmPassword = document.querySelector(`[data-password-indicator-confirmation][data-password-indicator-group="${group}"]`);

  // Create container to display password indicator.
  this.alert = document.createElement('div');
  this.alert.classList.add('civic-password-indicator');
  this.alert.classList.add(themeClass || 'civic-theme-light');
  this.alert.classList.add('civic-theme-light');
  this.alert.innerHTML = `
<div class="civic-password-indicator__progress">
  <span class="civic-password-indicator__progress-bar"></span>
  <span></span>
</div>
<div data-password-indicator-indicator-status class="civic-password-indicator__status"></div>
<div data-password-indicator-indicator-message class="civic-password-indicator__message"></div>`;
  this.alertBar = this.alert.querySelector('.civic-password-indicator__progress-bar');
  this.alertStatus = this.alert.querySelector('.civic-password-indicator__status');
  this.alertMessage = this.alert.querySelector('.civic-password-indicator__message');
  this.el.after(this.alert);

  // Create container to display confirm password state.
  this.confirm = document.createElement('div');
  this.confirm.classList.add('civic-password-indicator__message');
  this.elConfirmPassword.after(this.confirm);

  // Listeners.
  this.passwordCheckEvent = this.passwordCheck.bind(this);
  this.confirmPasswordEvent = this.confirmPassword.bind(this);

  this.el.addEventListener('input', this.passwordCheckEvent);
  this.elConfirmPassword.addEventListener('input', this.confirmPasswordEvent);

  // Initial test of password.
  this.updatePassword(this.el.value, this.elUsername.value, false);

  // Mark this element as processed.
  this.el.setAttribute('data-password-indicator', 'true');
}

CivicPasswordIndicator.prototype.evaluatePasswordStrength = function (password, passwordSettings) {
  password = password.trim();
  let indicatorText;
  let indicatorClass;
  let weaknesses = 0;
  let strength = 100;
  let msg = [];

  if (passwordSettings.checkSize) {
    const minSize = passwordSettings.checkMinSize;
    if (password.length < minSize) {
      msg.push(passwordSettings.tooShort);
      strength -= (minSize - password.length) * 5 + 30;
    }
  }

  if (passwordSettings.checkLower) {
    const hasLowercase = /[a-z]/.test(password);
    if (!hasLowercase) {
      msg.push(passwordSettings.addLowerCase);
      weaknesses += 1;
    }
  }

  if (passwordSettings.checkUpper) {
    const hasUppercase = /[A-Z]/.test(password);
    if (!hasUppercase) {
      msg.push(passwordSettings.addUpperCase);
      weaknesses += 1;
    }
  }

  if (passwordSettings.checkNumber) {
    const hasNumbers = /[0-9]/.test(password);
    if (!hasNumbers) {
      msg.push(passwordSettings.addNumbers);
      weaknesses += 1;
    }
  }

  if (passwordSettings.checkPunctuation) {
    const hasPunctuation = /[^a-zA-Z0-9]/.test(password);
    if (!hasPunctuation) {
      msg.push(passwordSettings.addPunctuation);
      weaknesses += 1;
    }
  }

  switch (weaknesses) {
    case 1:
      strength -= 12.5;
      break;

    case 2:
      strength -= 25;
      break;

    case 3:
      strength -= 40;
      break;

    case 4:
      strength -= 40;
      break;

    default:
      break;
  }

  if (passwordSettings.checkUsername) {
    const { username } = passwordSettings;
    if (password !== '' && password.toLowerCase() === username.toLowerCase()) {
      msg.push(passwordSettings.sameAsUsername);
      strength = 5;
    }
  }

  const cssClasses = {
    passwordWeak: 'weak',
    passwordFair: 'fair',
    passwordGood: 'good',
    passwordStrong: 'strong',
  };

  if (strength < 60) {
    indicatorText = passwordSettings.weak;
    indicatorClass = cssClasses.passwordWeak;
  } else if (strength < 70) {
    indicatorText = passwordSettings.fair;
    indicatorClass = cssClasses.passwordFair;
  } else if (strength < 80) {
    indicatorText = passwordSettings.good;
    indicatorClass = cssClasses.passwordGood;
  } else if (strength <= 100) {
    indicatorText = passwordSettings.strong;
    indicatorClass = cssClasses.passwordStrong;
  }

  const messageTips = msg;
  msg = msg.length > 0 ? ''.concat(passwordSettings.hasWeaknesses, '<ul><li>').concat(msg.join('</li><li>'), '</li></ul>') : '';
  return {
    strength,
    message: msg,
    indicatorText,
    indicatorClass,
    messageTips,
  };
};

CivicPasswordIndicator.prototype.updatePassword = function (password, username, showMessage) {
  const result = this.evaluatePasswordStrength(password, { ...this.settings, username });

  // Clear any existing progress classes.
  this.alert.classList.forEach((item) => {
    if (item.indexOf('civic-password-indicator--') === 0) {
      this.alert.classList.remove(item);
    }
  });
  // Add current progress class.
  this.alert.classList.add(`civic-password-indicator--${result.indicatorClass}`);
  this.alertStatus.innerHTML = result.indicatorText;
  if (showMessage) {
    this.alertMessage.innerHTML = result.message;
  }
};

CivicPasswordIndicator.prototype.passwordCheck = function (e) {
  this.updatePassword(e.target.value, this.elUsername.value, true);
  this.confirmPassword();
};

CivicPasswordIndicator.prototype.confirmPassword = function () {
  const password = this.el.value;
  const confirm = this.elConfirmPassword.value;

  if (password.length > 0 && confirm.length > 0) {
    if (password === confirm) {
      // Pass
      this.el.parentNode.classList.remove('civic-input--error');
      this.elConfirmPassword.parentNode.classList.remove('civic-input--error');
      this.confirm.innerHTML = 'Passwords match';
    } else {
      // Fail
      this.el.parentNode.classList.add('civic-input--error');
      this.elConfirmPassword.parentNode.classList.add('civic-input--error');
      this.confirm.innerHTML = 'Passwords do not match';
    }
  } else {
    this.el.parentNode.classList.remove('civic-input--error');
    this.elConfirmPassword.parentNode.classList.remove('civic-input--error');
    this.confirm.innerHTML = '';
  }
};

document.querySelectorAll('[data-password-indicator]').forEach((el) => {
  new CivicPasswordIndicator(el);
});
