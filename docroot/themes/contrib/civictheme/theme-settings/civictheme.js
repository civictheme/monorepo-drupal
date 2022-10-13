(function ($, Drupal) {
  Drupal.behaviors.ThemeBehaviours = {
    attach: function (context) {
      // Color mixer.
      function colorMixer(color, mixer ,number) {
        color = color.substring(1);
        mixer = mixer.substring(1);
        color = [parseInt(color[0] + color[1], 16), parseInt(color[2] + color[3], 16), parseInt(color[4] + color[5], 16)];
        mixer = [parseInt(mixer[0] + mixer[1], 16), parseInt(mixer[2] + mixer[3], 16), parseInt(mixer[4] + mixer[5], 16)];
        percentage = number / 100;
        var result = [
          (1 - percentage) * color[0] + percentage * mixer[0],
          (1 - percentage) * color[1] + percentage * mixer[1],
          (1 - percentage) * color[2] + percentage * mixer[2],
        ];
        result = '#' + int_to_hex(result[0]) + int_to_hex(result[1]) + int_to_hex(result[2]);
        return result;
      }

      function int_to_hex(num) {
        var hex = Math.round(num).toString(16);
        if (hex.length == 1) {
          hex = '0' + hex;
        }
        return hex;
      }

      // Color tint.
      function colorTint(color, number) {
        return colorMixer(color, '#000000' ,number);
      }

      // Color shade.
      function colorShade(color, number) {
        return colorMixer(color, '#ffffff' ,number);
      }

      if( $("#edit-colors-use-brand-colors").length && $("#edit-colors-brand").length) {
        if ($("#edit-colors-use-brand-colors").prop('checked', FALSE)) {
          $("#edit-colors-brand").hide();
        }
        $("#edit-colors-use-brand-colors").on("click",function () {
          $("#edit-colors-brand").toggle(this.checked);
        });
      }

      if( $("[id^='edit-colors-brand-']").length ) {
        if( $("#edit-colors-brand-light-brand2").length ) {
          $('fieldset.color-light').css('background-color', $("#edit-colors-brand-light-brand2").val());
        }
        if( $("#edit-colors-brand-dark-brand2").length ) {
          $('fieldset.color-dark').css('background-color', $("#edit-colors-brand-dark-brand2").val());
        }
        $("[id^='edit-colors-brand-']").change(function () {
          var light_brand1 = $("#edit-colors-brand-light-brand1").val();
          var light_brand2 = $("#edit-colors-brand-light-brand2").val();
          var light_brand3 = $("#edit-colors-brand-light-brand3").val();

          var dark_brand1 = $("#edit-colors-brand-dark-brand1").val();
          var dark_brand2 = $("#edit-colors-brand-dark-brand2").val();
          var dark_brand3 = $("#edit-colors-brand-dark-brand3").val();

          $('fieldset.color-light').css('background-color', light_brand2);
          $('fieldset.color-dark').css('background-color', dark_brand2);

          // Light.
          $("#edit-colors-palette-light-typography-heading").val(colorShade(light_brand1, 60));
          $("#edit-colors-palette-light-typography-body").val(colorTint(colorShade(light_brand1, 80), 20));
          $("#edit-colors-palette-light-background-background-light").val(colorTint(light_brand2, 90));
          $("#edit-colors-palette-light-background-background").val(light_brand2);
          $("#edit-colors-palette-light-background-background-dark").val(colorShade(light_brand2, 20));
          $("#edit-colors-palette-light-border-border-light").val(colorShade(light_brand2, 25));
          $("#edit-colors-palette-light-border-border").val(colorShade(light_brand2, 60));
          $("#edit-colors-palette-light-border-border-dark").val(colorShade(light_brand2, 90));
          $("#edit-colors-palette-light-interection-interaction-text").val(colorTint(light_brand2, 80));
          $("#edit-colors-palette-light-interection-interaction-background").val(light_brand1);
          $("#edit-colors-palette-light-interection-interaction-hover-text").val(colorTint(light_brand2, 80));
          $("#edit-colors-palette-light-interection-interaction-hover-background").val(colorShade(light_brand1, 40));
          $("#edit-colors-palette-light-highlight-highlight").val(light_brand3);

          // Dark.
          $("#edit-colors-palette-dark-typography-heading").val(colorTint(dark_brand1, 95));
          $("#edit-colors-palette-dark-typography-body").val(colorTint(dark_brand1, 85));
          $("#edit-colors-palette-dark-background-background-light").val(colorTint(dark_brand2, 5));
          $("#edit-colors-palette-dark-background-background").val(dark_brand2);
          $("#edit-colors-palette-dark-background-background-dark").val(colorShade(dark_brand2, 30));
          $("#edit-colors-palette-dark-border-border-light").val(colorTint(dark_brand2, 65));
          $("#edit-colors-palette-dark-border-border").val(colorTint(dark_brand2, 10));
          $("#edit-colors-palette-dark-border-border-dark").val(colorShade(dark_brand2, 30));
          $("#edit-colors-palette-dark-interection-interaction-text").val(dark_brand2);
          $("#edit-colors-palette-dark-interection-interaction-background").val(dark_brand1);
          $("#edit-colors-palette-dark-interection-interaction-hover-text").val(colorShade(dark_brand2, 30));
          $("#edit-colors-palette-dark-interection-interaction-hover-background").val(colorTint(dark_brand1, 40));
          $("#edit-colors-palette-dark-highlight-highlight").val(dark_brand3);

        });
      }
    }
}
})(jQuery, Drupal);
