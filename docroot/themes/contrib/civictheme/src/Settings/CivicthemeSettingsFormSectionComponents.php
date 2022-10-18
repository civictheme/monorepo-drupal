<?php

namespace Drupal\civictheme\Settings;

use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\CivicthemeUtility;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\File\Exception\FileException;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\Core\StreamWrapper\StreamWrapperManager;

/**
 * CivicTheme settings section to display components.
 */
class CivicthemeSettingsFormSectionComponents extends CivicthemeSettingsFormSectionBase {

  /**
   * {@inheritdoc}
   */
  public function weight() {
    return 3;
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
   */
  public function form(&$form, FormStateInterface &$form_state) {
    $form['components'] = [
      '#type' => 'vertical_tabs',
      '#title' => $this->t('CivicTheme components'),
      '#weight' => 50,
      '#tree' => TRUE,
    ];

    $form['components']['logo'] = [
      '#type' => 'details',
      '#title' => $this->t('Logo'),
      '#open' => TRUE,
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $breakpoints = ['desktop', 'mobile'];
    $logo_types = ['primary', 'secondary'];

    foreach (civictheme_theme_options() as $theme => $theme_label) {
      foreach ($logo_types as $logo_type) {
        $form['components']['logo'][$logo_type][$theme] = [
          '#type' => 'details',
          '#title' => $this->t('Logo @logo_type @theme', [
            '@theme' => $theme_label,
            '@logo_type' => $logo_type,
          ]),
          '#tree' => TRUE,
        ];
        foreach ($breakpoints as $breakpoint) {
          $form['components']['logo'][$logo_type][$theme]["image_{$logo_type}_{$theme}_{$breakpoint}_group"] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Logo @logo_type @theme @breakpoint', [
              '@theme' => $theme_label,
              '@breakpoint' => $breakpoint,
              '@logo_type' => $logo_type,
            ]),
            '#tree' => FALSE,
          ];

          $form['components']['logo'][$logo_type][$theme]["image_{$logo_type}_{$theme}_{$breakpoint}"] = [
            '#type' => 'textfield',
            '#title' => $this->t('Logo image for @logo_type in @theme theme for @breakpoint', [
              '@theme' => $theme_label,
              '@breakpoint' => $breakpoint,
              '@logo_type' => $logo_type,
            ]),
            '#description' => $this->getPathFieldDescription($this->getSetting("components.logo.image_{$logo_type}_{$theme}_{$breakpoint}"), "logo-{$logo_type}-{$theme}-{$breakpoint}.svg"),
            '#default_value' => $this->getPathFieldFriendlyPath($this->getSetting("components.logo.image_{$logo_type}_{$theme}_{$breakpoint}")),
            '#group' => "image_{$logo_type}_{$theme}_{$breakpoint}_group",
          ];

          $form['components']['logo'][$logo_type][$theme]["image_{$logo_type}_{$theme}_{$breakpoint}_group"]["image_{$logo_type}_{$theme}_{$breakpoint}_upload"] = [
            '#type' => 'file',
            '#title' => $this->t('Upload logo image for @logo_type in @theme theme for @breakpoint', [
              '@theme' => $theme_label,
              '@breakpoint' => $breakpoint,
              '@logo_type' => $logo_type,
            ]),
            '#maxlength' => 40,
            '#description' => $this->t("If you don't have direct file access to the server, use this field to upload your logo."),
            '#upload_validators' => [
              'file_validate_is_image' => [],
            ],
            '#tree' => FALSE,
            '#weight' => 1,
          ];
        }
      }
    }

    $form['components']['logo']['image_alt'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Logo image "alt" text'),
      '#description' => $this->t('Text for the <code>alt</code> attribute of the site logo image.'),
      '#default_value' => $this->getSetting('components.logo.image_alt'),
    ];

    $form['components']['header'] = [
      '#type' => 'details',
      '#title' => $this->t('Header'),
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $form['components']['header']['theme'] = [
      '#title' => $this->t('Theme'),
      '#description' => $this->t('Set the Header color theme.'),
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => civictheme_theme_options(),
      '#default_value' => $this->getSetting('components.header.theme') ?? CivicthemeConstants::HEADER_THEME_DEFAULT,
    ];

    $form['components']['header']['logo_type'] = [
      '#title' => $this->t('Logo type'),
      '#description' => $this->t('Logo type to appear in the Header.'),
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => civictheme_type_options(),
      '#default_value' => $this->getSetting('components.header.logo_type') ?? CivicthemeConstants::LOGO_TYPE_DEFAULT,
    ];

    $form['components']['footer'] = [
      '#type' => 'details',
      '#title' => $this->t('Footer'),
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $form['components']['footer']['theme'] = [
      '#title' => $this->t('Theme'),
      '#description' => $this->t('Set the Footer color theme.'),
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => civictheme_theme_options(),
      '#default_value' => $this->getSetting('components.footer.theme') ?? CivicthemeConstants::FOOTER_THEME_DEFAULT,
    ];

    $form['components']['footer']['logo_type'] = [
      '#title' => $this->t('Logo type'),
      '#description' => $this->t('Logo type to appear in the Footer.'),
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => civictheme_type_options(),
      '#default_value' => $this->getSetting('components.footer.logo_type') ?? CivicthemeConstants::LOGO_TYPE_DEFAULT,
    ];

    $form['components']['footer']['background_image'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Footer background image path'),
      '#description' => $this->getPathFieldDescription($this->getSetting('components.footer.background_image'), 'footer-background.png'),
      '#default_value' => $this->getPathFieldFriendlyPath($this->getSetting('components.footer.background_image')),
    ];

    $form['components']['navigation'] = [
      '#type' => 'details',
      '#title' => $this->t('Navigation'),
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $navigation_map = [
      'primary_navigation' => [
        'title' => $this->t('Primary navigation'),
        'dropdown' => CivicthemeConstants::NAVIGATION_DROPDOWN_DRAWER,
        'dropdown_columns' => 4,
        'dropdown_columns_fill' => FALSE,
        'is_animated' => FALSE,
      ],
      'secondary_navigation' => [
        'title' => $this->t('Secondary navigation'),
        'dropdown' => CivicthemeConstants::NAVIGATION_DROPDOWN_NONE,
        'dropdown_columns' => 4,
        'dropdown_columns_fill' => FALSE,
        'is_animated' => FALSE,
      ],
    ];

    foreach ($navigation_map as $navigation_name => $navigation_defaults) {
      $form['components']['navigation'][$navigation_name] = [
        '#type' => 'details',
        '#title' => $navigation_defaults['title'],
        '#tree' => TRUE,
        '#open' => TRUE,
      ];

      $form['components']['navigation'][$navigation_name]['dropdown'] = [
        '#title' => $this->t('Dropdown type'),
        '#description' => $this->t('Select how the menu sub-tree items would appear in the menu.'),
        '#type' => 'select',
        '#required' => TRUE,
        '#options' => [
          'none' => $this->t('None'),
          'dropdown' => $this->t('Dropdown'),
          'drawer' => $this->t('Drawer'),
        ],
        '#default_value' => $this->getSetting("components.navigation.$navigation_name.dropdown") ?? $navigation_defaults['dropdown'],
      ];

      $form['components']['navigation'][$navigation_name]['dropdown_columns'] = [
        '#title' => $this->t('Number of columns in the drawer row'),
        '#description' => $this->t('Number of menu columns per row. If there are more menus than items per row - they will flow on the next row.'),
        '#type' => 'number',
        '#min' => 1,
        '#max' => 4,
        '#default_value' => $this->getSetting("components.navigation.$navigation_name.dropdown_columns") ?? $navigation_defaults['dropdown_columns'],
        '#states' => [
          'visible' => [
            ':input[name="components[navigation][' . $navigation_name . '][dropdown]"]' => ['value' => CivicthemeConstants::NAVIGATION_DROPDOWN_DRAWER],
          ],
        ],
      ];

      $form['components']['navigation'][$navigation_name]['dropdown_columns_fill'] = [
        '#title' => $this->t('Fill width of the last drawer column'),
        '#description' => $this->t('Fill the width of the last column in the drawer. Useful for large menus.'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting("components.navigation.$navigation_name.dropdown_columns_fill") ?? $navigation_defaults['dropdown_columns_fill'],
        '#states' => [
          'visible' => [
            ':input[name="components[navigation][' . $navigation_name . '][dropdown]"]' => ['value' => CivicthemeConstants::NAVIGATION_DROPDOWN_DRAWER],
          ],
        ],
      ];

      $form['components']['navigation'][$navigation_name]['is_animated'] = [
        '#title' => $this->t('Animate'),
        '#description' => $this->t('Animate transitions.'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting("components.navigation.$navigation_name.is_animated") ?? $navigation_defaults['is_animated'],
        '#states' => [
          'visible' => [
            ':input[name="components[navigation][' . $navigation_name . '][dropdown]"]' => [
              ['value' => CivicthemeConstants::NAVIGATION_DROPDOWN_DROPDOWN],
              ['value' => CivicthemeConstants::NAVIGATION_DROPDOWN_DRAWER],
            ],
          ],
        ],
      ];
    }

    $form['components']['link'] = [
      '#type' => 'details',
      '#title' => $this->t('Link'),
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $form['components']['link']['new_window'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Open links in a new window'),
      '#description' => $this->t('Open internal and external links in a new browser window.'),
      '#default_value' => $this->getSetting('components.link.new_window'),
    ];

    $form['components']['link']['external_new_window'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Open external links in a new window'),
      '#description' => $this->t('Open all external links in a new browser window.'),
      '#default_value' => $this->getSetting('components.link.external_new_window'),
      '#states' => [
        'visible' => [
          ':input[name="components[link][new_window]"]' => ['checked' => FALSE],
        ],
      ],
    ];

    $form['components']['link']['external_override_domains'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Override external link domains'),
      '#description' => $this->t('A list of domains that should be considered as internal. External links matching these domains will not be displayed as external.<br/>One domain per line.<br/>Do not include trailing slash (/).<br/>Protocol is optional.'),
      '#default_value' => CivicthemeUtility::arrayToMultiline($this->getSetting('components.link.external_override_domains')),
      '#rows' => 4,
    ];

    foreach (array_keys($form['components']) as $component_name) {
      $validate = CivicthemeUtility::camelise("validate_$component_name");
      if (is_callable([$this, $validate])) {
        $form['#validate'][] = [$this, $validate];
      }

      $submit = CivicthemeUtility::camelise("submit_$component_name");
      if (is_callable([$this, $submit])) {
        $form['#submit'][] = [$this, $submit];
      }
    }
  }

  /**
   * Validate callback for theme settings form to check footer settings.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   * @SuppressWarnings(PHPMD.ElseExpression)
   */
  public function validateFooter(array $form, FormStateInterface &$form_state) {
    $field_name_key = ['components', 'footer', 'background_image'];
    $path = $form_state->getValue($field_name_key);

    if (!empty($path)) {
      $path = $this->normalizePath($path);
      if ($path) {
        $path = \Drupal::service('file_url_generator')->generateString($path);
        $form_state->setValue($field_name_key, ltrim($path, '/'));
      }
      else {
        $form_state->setErrorByName(implode('][', $field_name_key), $this->t('The image path is invalid.'));
      }
    }
  }

  /**
   * Validate callback for theme settings form of Link component.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function validateLink(array &$form, FormStateInterface $form_state) {
    $override_domain_field_name_keys = [
      'components',
      'link',
      'external_override_domains',
    ];
    $domains = $form_state->getValue($override_domain_field_name_keys, '');
    $domains = CivicthemeUtility::multilineToArray($domains);
    $invalid_domains = [];
    foreach ($domains as $domain) {
      // Allow to enter 'example.com' instead of 'http://example.com'.
      $domain_normalized = $this->externalLinkNormalizeDomain($domain);
      if (!UrlHelper::isValid($domain_normalized, TRUE)) {
        $invalid_domains[] = $domain;
        continue;
      }

      $domain_parts = parse_url($domain_normalized);
      if (!empty($domain_parts['path']) || !empty($domain_parts['query']) || !empty($domain_parts['fragment'])) {
        $invalid_domains[] = $domain;
      }
    }

    if (!empty($invalid_domains)) {
      $form_state->setErrorByName(implode('][', $override_domain_field_name_keys), $this->t('Domain values are not valid domains: %domains', [
        '%domains' => implode(', ', $invalid_domains),
      ]));

      return;
    }

    // Set field to converted array of links.
    $form_state->setValue($override_domain_field_name_keys, $domains);
  }

  /**
   * Validate callback for theme settings form of Logo component.
   */
  public function validateLogo(array &$form, FormStateInterface $form_state) {
    $breakpoints = ['desktop', 'mobile'];
    $logo_types = ['primary', 'secondary'];

    foreach (array_keys(civictheme_theme_options()) as $theme) {
      foreach ($logo_types as $logo_type) {
        foreach ($breakpoints as $breakpoint) {
          $field_name_key = [
            'components',
            'logo',
            $logo_type,
            $theme,
            "image_{$logo_type}_{$theme}_{$breakpoint}",
          ];

          $path = $form_state->getValue($field_name_key);

          $field_name_processed_key = [
            'components',
            'logo',
            "image_{$logo_type}_{$theme}_{$breakpoint}",
          ];

          // Check for a new uploaded logo.
          if (isset($form['components']['logo']["image_{$logo_type}_{$theme}_{$breakpoint}_group"]["image_{$logo_type}_{$theme}_{$breakpoint}_upload"])) {
            $file = _file_save_upload_from_form($form['components']['logo']["image_{$logo_type}_{$theme}_{$breakpoint}_group"]["image_{$logo_type}_{$theme}_{$breakpoint}_upload"], $form_state, 0, FileSystemInterface::EXISTS_REPLACE);
            if ($file) {
              // Put the temp file in form_values so we can save it on submit.
              $form_state->setValue("image_{$logo_type}_{$theme}_{$breakpoint}_upload", $file);
            }
          }

          if (!empty($path)) {
            $path = $this->normalizePath($path);
            if ($path) {
              $path = \Drupal::service('file_url_generator')->generateString($path);
              $form_state->setValue($field_name_processed_key, ltrim($path, '/'));
              continue;
            }
            $form_state->setErrorByName(implode('][', $field_name_key), $this->t('The image path is invalid.'));
          }
        }
      }
    }
  }

  /**
   * Submit callback for theme settings form of Logo component.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  public function submitLogo(array &$form, FormStateInterface $form_state) {
    $breakpoints = ['desktop', 'mobile'];
    $logo_types = ['primary', 'secondary'];

    $values = $form_state->getValues();

    foreach (array_keys(civictheme_theme_options()) as $theme) {
      foreach ($logo_types as $logo_type) {
        foreach ($breakpoints as $breakpoint) {
          $logo_field_name_key = [
            'components',
            'logo',
            "image_{$logo_type}_{$theme}_{$breakpoint}",
          ];
          $field_name_key = "image_{$logo_type}_{$theme}_{$breakpoint}_upload";

          // If the user uploaded a new logo - save it to a permanent location
          // and use it in place of the provided path.
          $default_scheme = \Drupal::config('system.file')->get('default_scheme');

          try {
            if (!empty($values[$field_name_key])) {
              $filename = \Drupal::service('file_system')->copy($values[$field_name_key]->getFileUri(), $default_scheme . '://');
              if (!empty($filename)) {
                $path = $this->normalizePath($filename);
                if ($path) {
                  $path = \Drupal::service('file_url_generator')->generateString($path);
                  $form_state->setValue($logo_field_name_key, ltrim($path, '/'));
                }
              }
            }
          }
          catch (FileException $e) {
            // Ignore.
          }

          $form_state->unsetValue($field_name_key);
        }
      }
    }
  }

  /**
   * Provide a description for a path field.
   *
   * @param string $original_path
   *   The original path from the current field value.
   * @param string $fallback_path
   *   Fallback file name.
   *
   * @return string
   *   Description string.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  protected function getPathFieldDescription($original_path, $fallback_path) {
    $theme_name = \Drupal::configFactory()->get('system.theme')->get('default');
    /** @var \Drupal\Core\Extension\ThemeHandler $theme_handler */
    $theme_handler = \Drupal::getContainer()->get('theme_handler');

    $friendly_path = $this->getPathFieldFriendlyPath($original_path);

    $local_file = \Drupal::theme()->getActiveTheme()->getPath() . '/' . $fallback_path;

    // Prepare local file path for description.
    if ($original_path && !empty($friendly_path)) {
      $local_file = strtr($original_path, ['public:/' => PublicStream::basePath()]);
    }
    elseif ($theme_name) {
      $local_file = $theme_handler->getTheme($theme_name)->getPath() . '/' . $fallback_path;
    }

    return $this->t('Examples:<br/><code>@implicit-public-file</code> (for a file in the public filesystem)<br/><code>@explicit-file</code><br/><code>@local-file</code>.', [
      '@implicit-public-file' => $friendly_path ?? $fallback_path,
      '@explicit-file' => StreamWrapperManager::getScheme($original_path ?? '') !== FALSE ? $original_path : 'public://' . $fallback_path,
      '@local-file' => $local_file,
    ]);
  }

  /**
   * Convert path to a human-friendly path.
   *
   * @param string $original_path
   *   The original path.
   *
   * @return string
   *   Friendly path or original path if an invalid stream wrapper was provided.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  protected function getPathFieldFriendlyPath($original_path) {
    // If path is a public:// URI, display the path relative to the 'files'
    // directory; stream wrappers are not end-user friendly.
    $friendly_path = NULL;

    if ($original_path && StreamWrapperManager::getScheme($original_path) == 'public') {
      $friendly_path = StreamWrapperManager::getTarget($original_path);
    }

    return $friendly_path ?? $original_path;
  }

  /**
   * Normalize domain.
   */
  protected function externalLinkNormalizeDomain($domain) {
    return _civictheme_external_link_normalize_domain($domain);
  }

  /**
   * Normalize file path.
   *
   * @param string $path
   *   A path relative to the Drupal root or to the public files directory, or
   *   a stream wrapper URI.
   *
   * @return mixed
   *   A valid path that can be displayed through the theme system, or FALSE if
   *   the path could not be validated.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  protected function normalizePath($path) {
    // Absolute local file paths are invalid.
    if ($this->fileSystem->realpath($path) == $path) {
      return FALSE;
    }

    // A path relative to the Drupal root or a fully qualified URI is valid.
    if (is_file($path)) {
      return $path;
    }

    // Prepend 'public://' for relative file paths within public filesystem.
    if (StreamWrapperManager::getScheme($path) === FALSE) {
      $path = 'public://' . $path;
    }

    if (is_file($path)) {
      return $path;
    }

    return FALSE;
  }

}
