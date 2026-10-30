/**
 * @file
 * CivicTheme theme color settings.
 * phpcs:disable Generic.PHP.UpperCaseConstant.Found
 */
(function ($, Drupal) {
  Drupal.behaviors.civicthemeColors = {
    attach: function (context) {
      var self = this;
      $('[name^="colors[brand]"]', context).each(function () {
        self.initDependants($(this), context);
        self.initLines($(this), context);
      });
    },
    initDependants: function ($source, context) {
      var self = this;
      $source.change(function () {
        var dependants = self.getDependantsBySource($(this).attr('name'), context);
        for (var i in dependants) {
          var dependant = dependants[i];
          dependant.update($(this).val());
        }
      });
    },
    getDependants: function (context) {
      var self = this;
      var deps = [];
      $('[data-color-formula]', context).each(function () {
        var $this = $(this);
        deps.push({
          $el: $this,
          formula: self.parseFormula($this.data('color-formula')),
          update: function (value) {
            if (this.$el.is('input')) {
              this.$el.val(self.applyFilters(value, this.formula.filters));
              this.$el.siblings('label').addClass('js-color-updated');
              this.$el.change(function () {
                $(this).siblings('label').removeClass('js-color-updated');
              });
            }
            else {
              this.$el.css({
                backgroundColor: value
              });
            }
          }
        });
      });
      return deps;
    },
    getDependantsBySource: function (source, context) {
      var found = [];

      var deps = this.getDependants(context);
      for (var i in deps) {
        for (var j in deps[i].formula) {
          if (deps[i].formula.source === source) {
            found.push(deps[i]);
          }
        }
      }

      return found;
    },
    parseFormula: function (value) {
      var formula = {
        source: null,
        filters: [],
      };

      value = value || '';

      var formulaParts = value.split('|');
      formula.source = formulaParts.shift();

      for (var i in formulaParts) {
        var filterParts = formulaParts[i].split(',');
        var filterName = filterParts.shift();
        // Check if the filter is available.
        if (!(filterName in this.filters())) {
          continue;
        }

        formula.filters.push({
          name: filterName,
          args: filterParts,
        });
      }

      return formula;
    },
    applyFilters: function (value, filters) {
      for (var i in filters) {
        value = this.filters()[filters[i].name].apply(null, [value].concat(filters[i].args));
      }
      return value;
    },
    filters: function () {
      var self = this;
      return {
        shade: function (color, number) {
          return self.colorMix(color, '#000', number)
        },
        tint: function (color, number) {
          return self.colorMix(color, '#fff', number)
        },
      };
    },
    colorMix: function (color, mixer, number) {
      number = Math.max(0, Math.min(parseInt(number), 100));
      color = this.colorHexToRgb(color);
      mixer = this.colorHexToRgb(mixer);
      percentage = number / 100;

      var result = [
        (1 - percentage) * color[0] + percentage * mixer[0],
        (1 - percentage) * color[1] + percentage * mixer[1],
        (1 - percentage) * color[2] + percentage * mixer[2],
      ];
      result = '#' + this.intToHex(result[0]) + this.intToHex(result[1]) + this.intToHex(result[2]);
      return result;
    },
    colorNormalize: function (value, preserveHash) {
      if (typeof value !== 'string') {
        throw new Error('Non-string color value provided.');
      }

      value = value.startsWith('#') && !preserveHash ? value.substring(1) : value;

      if (value.length !== 3 && value.length !== 6) {
        throw new Error('Invalid color value format provided.');
      }

      if (value.length === 3) {
        value = value.split('').map(function (v) {
          return v + v;
        }).join('');
      }

      return value;
    },
    colorBrightness: function (value) {
      value = this.colorHexToRgb(value);
      return Math.round(
        (
          (value[0] * 299) +
          (value[1] * 587) +
          (value[2] * 114)
        ) / 1000
      );
    },
    colorHexToRgb: function (value) {
      value = this.colorNormalize(value);
      return [parseInt(value[0] + value[1], 16), parseInt(value[2] + value[3], 16), parseInt(value[4] + value[5], 16)];
    },
    intToHex: function (value) {
      var hex = Math.floor(value).toString(16);
      if (hex.length === 1) {
        hex = '0' + hex;
      }
      return hex;
    },
    initLines: function ($source, $destination, context) {
      var self = this;
      var name = $source.attr('name').replace('-show-arrows', '') + '-show-arrows';
      var $checkbox = $('[name="' + name + '"]');
      if (!$checkbox.length) {
        var id = 'id-' + Date.now() + Math.floor(Math.random() * 26);
        var $wrapper = $('<div class="js-show-dependants-container"><input type="checkbox" id="' + id + '" name="' + name + '"><label for="' + id + '">Show dependants</label></div>').insertBefore($source);
        $checkbox = $wrapper.find('[name="' + name + '"]');
        $checkbox.change(function () {
          var sourceName = $(this).attr('name').replace('-show-arrows', '');
          var $source = $('[name="' + sourceName + '"]', context);
          var val = $source.val();
          if ($(this).is(':checked')) {
            var dependants = self.getDependantsBySource(sourceName, context);
            for (var i in dependants) {
              var brightness = self.colorBrightness(val);
              self.drawLine($source, dependants[i].$el, {
                color: brightness < 128 ? val : self.filters().shade(val, (brightness * 100 / 256) - 50)
              })
            }
          }
          else {
            self.removeLines($('[name="' + sourceName + '"]', context))
          }
        });
      }
    },
    drawLine: function ($source, $destination, options) {
      options = $.extend(true, {}, {
        path: 'fluid',
        dash: {
          animation: false
        },
        animOptions: {
          duration: 3000
        },
      }, options);

      new LeaderLine(
        $source.get(0),
        $destination.get(0),
        options
      );

      $('body>.leader-line:last-child').data('source', $source.attr('name'));
    },
    removeLines: function ($source) {
      $('.leader-line').each(function () {
        if ($(this).data('source') === $source.attr('name')) {
          $(this).remove();
        }
      });
    }
  }
})(jQuery, Drupal);
