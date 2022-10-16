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
class CivicthemeSettingsSectionColors extends CivicthemeSettingsAbstractSection {

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
   * Field map with filters grouped by type.
   *
   * Used to generate color input with dependencies between colors and
   * filter pipeline.
   *
   * @return array
   *   Array of field map keyed by the theme and group name with values as
   *   arrays with color names as keys and color/filter mapping pipeline as
   *   values.
   *   The values are piped through a pipeline of filters.
   *   The pipeline consists of elements divided by pipe (|):
   *   - color name (as it will appear on the form)
   *   - filter name and 0 or more comma-delimited list of filter arguments.
   */
  protected function fieldMap() {
    return [
      'light' => [
        'typography' => [
          'heading' => 'brand1|shade,60',
          'body' => 'brand1|shade,80|tint,20',
        ],
        'background' => [
          'background-light' => 'brand2|tint,90',
          'background' => 'brand2',
          'background-dark' => 'brand2|shade,20',
        ],
        'border' => [
          'border-light' => 'brand2|shade,25',
          'border' => 'brand2|shade,60',
          'border-dark' => 'brand2|shade,90',
        ],
        'interaction' => [
          'interaction-text' => 'brand2|tint,80',
          'interaction-background' => 'brand1',
          'interaction-hover-text' => 'brand2|tint,80',
          'interaction-hover-background' => 'brand1|shade,40',
          'interaction-focus' => FALSE,
        ],
        'highlight' => [
          'highlight' => 'brand3',
        ],
        'status' => [
          'information' => FALSE,
          'warning' => FALSE,
          'error' => FALSE,
          'success' => FALSE,
        ],
      ],
      'dark' => [
        'typography' => [
          'heading' => 'brand1|tint,95',
          'body' => 'brand1|tint,85',
        ],
        'background' => [
          'background-light' => 'brand2|tint,5',
          'background' => 'brand2',
          'background-dark' => 'brand2|shade,30',
        ],
        'border' => [
          'border-light' => 'brand2|tint,65',
          'border' => 'brand2|tint,10',
          'border-dark' => 'brand2|shade,30',
        ],
        'interaction' => [
          'interaction-text' => 'brand2',
          'interaction-background' => 'brand1',
          'interaction-hover-text' => 'brand2|shade,30',
          'interaction-hover-background' => 'brand1|tint,40',
          'interaction-focus' => FALSE,
        ],
        'highlight' => [
          'highlight' => 'brand3',
        ],
        'status' => [
          'information' => FALSE,
          'warning' => FALSE,
          'error' => FALSE,
          'success' => FALSE,
        ],
      ],
    ];
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
    $color_map = [];

    $group_map = $this->fieldMap();

    $colors = $this->colorManager->getCssColors();
    foreach ($group_map as $theme_name => $group_theme_map) {
      foreach ($group_theme_map as $group_name => $group) {
        foreach ($group as $group_color_name => $group_color_formula) {
          $group_color_name_field = str_replace('-', '_', $group_color_name);

          // Default value.
          $color_value = $theme_name == CivicthemeConstants::THEME_LIGHT ? '#000000' : '#ffffff';

          // Value from provided colors.
          if (!empty($colors[$theme_name]) && array_key_exists($group_color_name, $colors[$theme_name])) {
            $color_value = $colors[$theme_name][$group_color_name]['value'];
          }

          $color_map[$theme_name][$group_name][$group_color_name_field] = [
            'value' => $color_value,
            'formula' => CivicthemeColorManager::processColorFormula($group_color_formula, $theme_name),
          ];
        }
      }
    }

    return $color_map;
  }

}
