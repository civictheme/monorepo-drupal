<?php

declare(strict_types=1);

namespace Drupal\civictheme\Settings;

use Drupal\civictheme\CivicthemeColorManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\CivicthemeUtility;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CivicTheme settings section to display colors.
 */
class CivicthemeSettingsFormSectionColors extends CivicthemeSettingsFormSectionBase {

  /**
   * The color manager.
   *
   * @var \Drupal\civictheme\CivicthemeColorManager
   */
  protected $colorManager;

  /**
   * {@inheritdoc}
   */
  public function weight(): int {
    return 20;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    $instance = parent::create($container);
    $instance->setColorManager($container->get('class_resolver')->getInstanceFromDefinition(CivicthemeColorManager::class));

    return $instance;
  }

  /**
   * Set color manager service.
   */
  public function setColorManager(CivicthemeColorManager $color_manager): void {
    $this->colorManager = $color_manager;
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
   */
  public function form(array &$form, FormStateInterface $form_state): void {
    $values_map = $this->fieldValuesMap();

    if (empty($values_map)) {
      return;
    }

    $form['colors'] = [
      '#type' => 'details',
      '#title' => $this->t('Colors'),
      '#weight' => 20,
      '#open' => TRUE,
      '#tree' => TRUE,
      '#description' => $this->t('<p>Website colors can be specified via:</p><ul><li><strong>CSS code</strong><br>Uncheck <code>Use Color Selector</code> below.</li><li><strong>Color Selector</strong><br>Check <code>Use Color Selector</code> below.</li><li><strong>CSS code with Color Selector overrides</strong><br>Check <code>Use Color Selector</code> below. Note that this will require to keep CSS synchronised with Color Selector values. This approach is not recommended and should be used as a last resort.</li></ul>'),
    ];

    $form['colors']['information'] = [
      '#type' => 'details',
      '#title' => $this->t('More information'),
      '#open' => FALSE,
      '#description' => $this->t('<h3>Color values inheritance</h3><p><code>Palette CSS variable (CSS ot Color Selector) -&gt; Component CSS variable -&gt; Component CSS property</code></p><p>For example, consider the Title of the Promo component.</p><p>The CSS property <code>color</code> has a value of the <code>--ct-promo-light-title-color</code>, which is set to Palette Color CSS variable <code>--ct-color-light-heading</code> by default, which in own turn is set to a value of a colour specified in CSS.</p><p>When Palette Color CSS variable <code>--ct-color-light-heading</code> updated - all values that are based of this value are updated as well. This means that the <code>color</code> CSS property of the Title of the Promo component will be updated to a new value of the <code>--ct-color-light-heading</code> CSS variable.</p><p>If the Title of the Promo component need to have a completely custom color, the variable <code>--ct-promo-light-title-color</code> can be set to a required value in CSS, which will "unlink" it from the value of <code>--ct-color-light-heading</code>.</p><p><h3>Color Selector</h3></p><p>Color Selector produces <em>Palette colors</em> values as CSS variables in dynamically generated stylesheet (<code>public://css-variables.your_theme_name.css</code>), which re-writes CSS variables stylesheet included as a <code>css-variables</code> library. This means that the generated stylesheet is included in DOM after CSS stylesheet.</p><p>Colors in <em>Palette colors</em> allow to define the colors for components in <em>Light</em> and <em>Dark</em> themes.</p><p><em>Palette colors</em> can be set manually or using shorthand <em>Brand colors</em> with pre-defined color formulas (check <em>Show dependants</em> to see which <em>Brand color</em> drives which <em>Palette color</em>).</p>'),
    ];

    $form['colors']['use_color_selector'] = [
      '#title' => $this->t('Use Color Selector'),
      '#type' => 'checkbox',
      '#default_value' => $this->themeConfigManager->load('colors.use_color_selector', FALSE),
    ];

    $form['colors']['use_brand_colors'] = [
      '#title' => $this->t('Use Brand colors'),
      '#type' => 'checkbox',
      '#default_value' => $this->themeConfigManager->load('colors.use_brand_colors', TRUE),
      '#states' => [
        'visible' => [
          'input[name="colors[use_color_selector]"' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['colors']['brand'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Brand colors'),
      '#tree' => TRUE,
      '#attributes' => [
        'class' => [
          'civictheme-layout-2col',
          'civictheme-reset-fieldset',
        ],
      ],
      '#states' => [
        'visible' => [
          'input[name="colors[use_color_selector]"' => ['checked' => TRUE],
          'input[name="colors[use_brand_colors]"' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['colors']['palette'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Palette colors'),
      '#tree' => TRUE,
      '#attributes' => [
        'class' => [
          'civictheme-layout-2col',
          'civictheme-reset-fieldset',
        ],
      ],
      '#states' => [
        'visible' => [
          'input[name="colors[use_color_selector]"' => ['checked' => TRUE],
        ],
      ],
    ];

    $brand_map = [
      'brand1',
      'brand2',
      'brand3',
    ];
    foreach (civictheme_theme_options() as $theme => $theme_label) {
      $form['colors']['brand'][$theme] = [
        '#type' => 'fieldset',
        '#title' => CivicthemeUtility::toLabel($theme_label),
        '#tree' => TRUE,
        '#attributes' => [
          'class' => ['civictheme-layout-cols'],
        ],
      ];

      foreach ($brand_map as $name) {
        $setting_name = implode('.', ['colors', 'brand', $theme, $name]);
        // @phpstan-ignore-next-line
        $form['colors']['brand'][$theme][$name] = [
          '#type' => 'color',
          '#title_display' => 'after',
          '#title' => CivicthemeUtility::toLabel($name),
          '#default_value' => $this->themeConfigManager->load($setting_name, $theme == CivicthemeConstants::THEME_LIGHT ? '#000000' : '#ffffff'),
          '#attributes' => [
            'class' => ['civictheme-input-color'],
          ],
        ];
      }
    }

    foreach ($values_map as $theme => $group) {
      $form['colors']['palette'][$theme] = [
        '#type' => 'fieldset',
        '#title' => CivicthemeUtility::toLabel(civictheme_theme_options()[$theme]),
        '#tree' => TRUE,
      ];

      foreach ($group as $group_name => $colors) {
        // @phpstan-ignore-next-line
        $form['colors']['palette'][$theme][$group_name] = [
          '#type' => 'fieldset',
          '#title' => CivicthemeUtility::toLabel($group_name),
          '#tree' => TRUE,
          '#attributes' => [
            'class' => [
              'civictheme-layout-cols',
              'civictheme-reset-fieldset',
            ],
          ],
        ];

        foreach ($colors as $name => $value) {
          $setting_name = implode('.', ['colors', 'palette', $theme, $name]);
          $form['colors']['palette'][$theme][$group_name][$name] = [
            '#type' => 'color',
            '#title_display' => 'after',
            '#title' => CivicthemeUtility::toLabel($name),
            '#default_value' => $this->themeConfigManager->load($setting_name, $value['value']),
            '#tree' => TRUE,
            '#attributes' => [
              'class' => ['civictheme-input-color'],
              // Formula is passed to the FE to then allow Brand colors to
              // update Palette colors.
              'data-color-formula' => $value['formula'],
            ],
          ];
        }
      }
    }

    $form['#submit'][] = $this->submitColors(...);
    $form['#attached']['library'][] = 'civictheme/theme-settings.colors';
  }

  /**
   * Submit callback for theme settings form of colors.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function submitColors(array &$form, FormStateInterface $form_state): void {
    // Remove grouping of Palette color values.
    $colors = $form_state->getValue('colors');
    foreach (civictheme_theme_options(TRUE) as $theme) {
      foreach ($colors['palette'][$theme] as $group => $values) {
        foreach ($values as $key => $value) {
          $old_key = ['colors', 'palette', $theme, $group];
          $new_key = ['colors', 'palette', $theme, $key];
          $form_state->setValue($new_key, $value);
          if (!array_key_exists($group, $values)) {
            $form_state->unsetValue($old_key);
          }
        }
      }
    }

    // Invalidate caches only if changes were made.
    if (Json::encode($form_state->getValue('colors')) != Json::encode($this->themeConfigManager->load('colors'))) {
      $this->colorManager->invalidateCache();
      $this->messenger->addMessage('Color selector cache was reset.');
    }
  }

  /**
   * A map of field values based on the field map and discovered CSS colors.
   *
   * @return array<string, array<string, array<string, array<string, string|null>>>>.
   *   Map with theme, group and color parent keys and a value of:
   *   - value: (string) Color value.
   *   - formula: (string) Color calculation formula.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  protected function fieldValuesMap(): array {
    $field_values = [];

    $formula_map = CivicthemeColorManager::colorPaletteMap();
    $colors = $this->colorManager->getColors(CivicthemeColorManager::COLOR_TYPE_PALETTE);
    foreach ($formula_map as $theme_name => $group_theme_map) {
      foreach ($group_theme_map as $group_name => $group) {
        // @phpstan-ignore-next-line
        foreach (array_keys($group) as $group_color_name) {
          $group_color_name_field = str_replace('-', '_', (string) $group_color_name);

          /** @var \Drupal\civictheme\Color\CivicthemeColor $color */
          $color = $colors[$theme_name][$group_color_name] ?? FALSE;

          $field_values[$theme_name][$group_name][$group_color_name_field] = [
            // @phpstan-ignore-next-line
            'value' => $color ? $color->getValue() : CivicthemeColorManager::COLOR_DEFAULT,
            // @phpstan-ignore-next-line
            'formula' => $color && $color->getFormula() ? self::processColorFormula($color->getFormula(), $theme_name) : NULL,
          ];
        }
      }
    }

    return $field_values;
  }

  /**
   * Process color formula.
   */
  protected static function processColorFormula(string $formula, string $theme): string {
    $parts = explode('|', $formula);
    $name = array_shift($parts);
    $name = sprintf('colors[brand][%s][%s]', $theme, $name);
    array_unshift($parts, $name);

    return implode('|', $parts);
  }

}
