<?php

namespace Drupal\civictheme\Settings;

use Drupal\civictheme\CivicthemeColorManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\CivicthemeUtility;
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
  public function weight() {
    return 2;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->setColorManager($container->get('class_resolver')->getInstanceFromDefinition(CivicthemeColorManager::class));

    return $instance;
  }

  /**
   * Set color manager service.
   */
  public function setColorManager(CivicthemeColorManager $color_manager) {
    $this->colorManager = $color_manager;
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
   */
  public function form(&$form, FormStateInterface &$form_state) {
    $values_map = $this->fieldValuesMap();

    if (empty($values_map)) {
      return;
    }

    $form['colors'] = [
      '#type' => 'details',
      '#title' => $this->t('Colors'),
      '#weight' => 40,
      '#open' => TRUE,
      '#tree' => TRUE,
      '#description' => $this->t('Colors in <em>Palette colors</em> allow to define the colors for components in <em>Light</em> and <em>Dark</em> themes.<br/><br/><em>Palette colors</em> can be set manually or using shorthand <em>Brand colors</em> with pre-defined color formulas.'),
    ];

    $form['colors']['use_brand_colors'] = [
      '#title' => $this->t('Use Brand colors'),
      '#type' => 'checkbox',
      '#default_value' => theme_get_setting('colors.use_brand_colors') ?? TRUE,
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
        $form['colors']['brand'][$theme][$name] = [
          '#type' => 'color',
          '#title_display' => 'after',
          '#title' => CivicthemeUtility::toLabel($name),
          '#default_value' => theme_get_setting($setting_name) ?? ($theme == CivicthemeConstants::THEME_LIGHT ? '#000000' : '#ffffff'),
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
            '#default_value' => theme_get_setting($setting_name) ?? $value['value'],
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

    $form['#submit'][] = [$this, 'submitColors'];
    $form['#attached']['library'][] = 'civictheme/theme-settings.colors';
  }

  /**
   * Submit callback for theme settings form of colors.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  public function submitColors(array &$form, FormStateInterface $form_state) {
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

    $this->colorManager->invalidateCache();
  }

  /**
   * A map of field values based on the field map and discovered CSS colors.
   *
   * @return array
   *   Map with theme, group and color parent keys and a value of:
   *   - value: (string) Color value.
   *   - formula: (string) Color calculation formula.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  protected function fieldValuesMap() {
    $field_values = [];

    $formula_map = CivicthemeColorManager::colorPaletteMap();
    $colors = $this->colorManager->getColors(CivicthemeColorManager::COLOR_TYPE_PALETTE);
    foreach ($formula_map as $theme_name => $group_theme_map) {
      foreach ($group_theme_map as $group_name => $group) {
        foreach (array_keys($group) as $group_color_name) {
          $group_color_name_field = str_replace('-', '_', $group_color_name);

          /** @var \Drupal\civictheme\Color\CivicthemeColor $color */
          $color = $colors[$theme_name][$group_color_name] ?? FALSE;

          $field_values[$theme_name][$group_name][$group_color_name_field] = [
            'value' => $color ? $color->getValue() : CivicthemeColorManager::COLOR_DEFAULT,
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
  protected static function processColorFormula($formula, $theme) {
    $parts = explode('|', $formula);
    $name = array_shift($parts);
    $name = "colors[brand][$theme][$name]";
    array_unshift($parts, $name);

    return implode('|', $parts);
  }

}
