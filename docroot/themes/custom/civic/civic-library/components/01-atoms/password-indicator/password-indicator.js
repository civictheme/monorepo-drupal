function CivicPasswordIndicator(el) {
  this.settings = {
    tooShort: 'Your password is too short',
    addLowerCase: 'Your password needs lowercase letters',
    addUpperCase: 'Your password needs uppercase letters',
    addNumbers: 'Your password needs numbers',
    addPunctuation: 'Your password needs punctuation',
    sameAsUsername: 'Your password is the same as your username',
    weak: 'Your password is very weak',
    fair: 'Your password is so-so',
    good: 'Your password is good',
    strong: 'Your password is great',
    hasWeaknesses: 'Your password has weaknesses',
  };

  this.el = el;

  const group = el.getAttribute('data-password-indicator-group');
  this.elUsername = document.querySelector(`[data-password-indicator-username][data-password-indicator-group="${group}"]`);
  this.elConfirmPassword = document.querySelector(`[data-password-indicator-confirmation][data-password-indicator-group="${group}"]`);

  // Create container to display password indicator.
  this.alert = document.createElement('div');
  this.alert.classList.add('civic-password-indicator');
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
}

CivicPasswordIndicator.prototype.evaluatePasswordStrength = function (password, passwordSettings) {
  password = password.trim();
  let indicatorText;
  let indicatorClass;
  let weaknesses = 0;
  let strength = 100;
  let msg = [];
  const hasLowercase = /[a-z]/.test(password);
  const hasUppercase = /[A-Z]/.test(password);
  const hasNumbers = /[0-9]/.test(password);
  const hasPunctuation = /[^a-zA-Z0-9]/.test(password);
  const { username } = passwordSettings;

  if (password.length < 12) {
    msg.push(passwordSettings.tooShort);
    strength -= (12 - password.length) * 5 + 30;
  }

  if (!hasLowercase) {
    msg.push(passwordSettings.addLowerCase);
    weaknesses += 1;
  }

  if (!hasUppercase) {
    msg.push(passwordSettings.addUpperCase);
    weaknesses += 1;
  }

  if (!hasNumbers) {
    msg.push(passwordSettings.addNumbers);
    weaknesses += 1;
  }

  if (!hasPunctuation) {
    msg.push(passwordSettings.addPunctuation);
    weaknesses += 1;
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

  if (password !== '' && password.toLowerCase() === username.toLowerCase()) {
    msg.push(passwordSettings.sameAsUsername);
    strength = 5;
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
    if (item !== 'civic-password-indicator') {
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
