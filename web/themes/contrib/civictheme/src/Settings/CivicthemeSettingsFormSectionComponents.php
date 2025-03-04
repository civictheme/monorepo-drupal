<?php

namespace Drupal\civictheme\Settings;

use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\CivicthemeUtility;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Form\FormStateInterface;

/**
 * CivicTheme settings section to display components.
 */
class CivicthemeSettingsFormSectionComponents extends CivicthemeSettingsFormSectionBase {

  /**
   * {@inheritdoc}
   */
  public function weight(): int {
    return 30;
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
   */
  public function form(array &$form, FormStateInterface $form_state): void {
    $form['components'] = [
      '#type' => 'vertical_tabs',
      '#title' => $this->t('Components'),
      '#weight' => 30,
      '#tree' => TRUE,
    ];

    $form['components']['logo'] = [
      '#type' => 'details',
      '#title' => $this->t('Logo'),
      '#open' => TRUE,
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $form['components']['logo']['information'] = [
      '#type' => 'markup',
      '#markup' => '<p>Site logo may consist of mandatory Primary and optional Secondary logos.</p><p><img width="430" src="' . $this->getCivicthemeThemeSettingsAssetUri('logo.png') . '"/></p>',
    ];

    $breakpoints = ['desktop', 'mobile'];
    $logo_types = ['primary', 'secondary'];

    foreach ($logo_types as $logo_type) {
      $form['components']['logo'][$logo_type] = [
        '#type' => 'details',
        '#title' => $this->t('@logo_type logo', [
          '@logo_type' => ucfirst($logo_type),
        ]),
      ];
      foreach (civictheme_theme_options() as $theme => $theme_label) {
        foreach ($breakpoints as $breakpoint) {
          // @phpstan-ignore-next-line
          $form['components']['logo'][$logo_type][$theme][$breakpoint] = [
            '#type' => 'fieldset',
            '#title' => $this->t('@logo_type logo @theme @breakpoint', [
              '@theme' => $theme_label,
              '@breakpoint' => ucfirst($breakpoint),
              '@logo_type' => ucfirst($logo_type),
            ]),
            '#description' => $this->t('Provide a path to an existing file or upload a new file.'),
            '#description_display' => 'before',
          ];

          $form['components']['logo'][$logo_type][$theme][$breakpoint]['path'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Image path for @logo_type @theme logo for @breakpoint', [
              '@theme' => $theme_label,
              '@breakpoint' => ucfirst($breakpoint),
              '@logo_type' => ucfirst($logo_type),
            ]),
            '#description' => $this->getPathFieldDescription(sprintf('logo-%s-%s-%s.svg', $logo_type, $theme, $breakpoint)),
            '#default_value' => $this->themeConfigManager->load(sprintf('components.logo.%s.%s.%s.path', $logo_type, $theme, $breakpoint)),
          ];

          $allowed_extensions = $this->imageFactory->getSupportedExtensions();
          $allowed_extensions[] = 'svg';
          $form['components']['logo'][$logo_type][$theme][$breakpoint]['upload'] = [
            '#type' => 'file',
            '#title' => $this->t('Upload @logo_type @theme logo for @breakpoint', [
              '@theme' => $theme_label,
              '@breakpoint' => ucfirst($breakpoint),
              '@logo_type' => ucfirst($logo_type),
            ]),
            '#name' => sprintf('files[components_logo_%s_%s_%s_upload]', $logo_type, $theme, $breakpoint),
            '#description' => $this->t("Uploading a file will replace the image path above. File will be uploaded into <code>@public</code> directory and will replace an existing file with the same name.", [
              '@public' => rtrim($this->toFriendlyFilePath($this->getDefaultFileScheme()), '/'),
            ]),
            '#upload_validators' => [
              'file_validate_extensions' => [implode(' ', $allowed_extensions)],
            ],
          ];
        }
      }
    }

    $form['components']['logo']['image_alt'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Logo image "alt" text'),
      '#description' => $this->t('Text for the <code>alt</code> attribute of the site logo image.'),
      '#default_value' => $this->themeConfigManager->load('components.logo.image_alt'),
    ];

    $form['components']['site_slogan'] = [
      '#type' => 'details',
      '#title' => $this->t('Site slogan'),
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $form['components']['site_slogan']['content'] = [
      '#title' => $this->t('Content'),
      '#description' => $this->t('Set the site slogan.'),
      '#type' => 'textfield',
      '#required' => FALSE,
      '#min' => 0,
      '#default_value' => $this->themeConfigManager->load('components.site_slogan.content'),
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
      '#default_value' => $this->themeConfigManager->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT),
    ];

    $form['components']['header']['logo_type'] = [
      '#title' => $this->t('Logo type'),
      '#description' => $this->t('Logo type to appear in the Header.'),
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => civictheme_type_options(),
      '#default_value' => $this->themeConfigManager->load('components.header.logo_type', CivicthemeConstants::LOGO_TYPE_DEFAULT),
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
      '#default_value' => $this->themeConfigManager->load('components.footer.theme', CivicthemeConstants::FOOTER_THEME_DEFAULT),
    ];

    $form['components']['footer']['logo_type'] = [
      '#title' => $this->t('Logo type'),
      '#description' => $this->t('Logo type to appear in the Footer.'),
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => civictheme_type_options(),
      '#default_value' => $this->themeConfigManager->load('components.footer.logo_type', CivicthemeConstants::LOGO_TYPE_DEFAULT),
    ];

    $form['components']['footer']['background_image'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Footer background image'),
      '#description' => $this->t('Provide a path to an existing file or upload a new file.'),
      '#description_display' => 'before',
      '#tree' => TRUE,
    ];

    $form['components']['footer']['background_image']['path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Footer background image path'),
      '#description' => $this->getPathFieldDescription('footer-background.png'),
      '#default_value' => $this->themeConfigManager->load('components.footer.background_image.path'),
    ];

    $form['components']['footer']['background_image']['upload'] = [
      '#type' => 'file',
      '#title' => $this->t('Upload footer background image'),
      '#name' => "files[components_footer_background_image_upload]",
      '#description' => $this->t("Uploading a file will replace the image path above. File will be uploaded into <code>@public</code> directory and will replace an existing file with the same name.", [
        '@public' => rtrim($this->toFriendlyFilePath($this->getDefaultFileScheme()), '/'),
      ]),
      '#upload_validators' => [
        'file_validate_is_image' => [],
      ],
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
        '#default_value' => $this->themeConfigManager->load(sprintf('components.navigation.%s.dropdown', $navigation_name), $navigation_defaults['dropdown']),
      ];

      $form['components']['navigation'][$navigation_name]['dropdown_columns'] = [
        '#title' => $this->t('Number of columns in the drawer row'),
        '#description' => $this->t('Number of menu columns per row. If there are more menus than items per row - they will flow on the next row.'),
        '#type' => 'number',
        '#min' => 1,
        '#max' => 4,
        '#default_value' => $this->themeConfigManager->load(sprintf('components.navigation.%s.dropdown_columns', $navigation_name), $navigation_defaults['dropdown_columns']),
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
        '#default_value' => $this->themeConfigManager->load(sprintf('components.navigation.%s.dropdown_columns_fill', $navigation_name), $navigation_defaults['dropdown_columns_fill']),
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
        '#default_value' => $this->themeConfigManager->load(sprintf('components.navigation.%s.is_animated', $navigation_name), $navigation_defaults['is_animated']),
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
      '#default_value' => $this->themeConfigManager->load('components.link.new_window'),
    ];

    $form['components']['link']['external_new_window'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Open external links in a new window'),
      '#description' => $this->t('Open all external links in a new browser window.'),
      '#default_value' => $this->themeConfigManager->load('components.link.external_new_window'),
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
      '#default_value' => CivicthemeUtility::arrayToMultiline($this->themeConfigManager->load('components.link.external_override_domains', [])),
      '#rows' => 4,
    ];

    $form['components']['skip_link'] = [
      '#type' => 'details',
      '#title' => $this->t('Skip Link'),
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $form['components']['skip_link']['theme'] = [
      '#title' => $this->t('Theme'),
      '#description' => $this->t('Set the Skip Link color theme.'),
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => civictheme_theme_options(),
      '#default_value' => $this->themeConfigManager->load('components.skip_link.theme', CivicthemeConstants::HEADER_THEME_DEFAULT),
    ];

    $form['components']['event_card'] = [
      '#type' => 'details',
      '#title' => $this->t('Event card'),
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $form['components']['event_card']['summary_length'] = [
      '#title' => $this->t('Summary length'),
      '#description' => $this->t('Set the length of the Summary field: the content will be trimmed to this length and ellipsis will be added. Set to 0 for no limit.'),
      '#type' => 'number',
      '#required' => TRUE,
      '#min' => 0,
      '#default_value' => $this->themeConfigManager->loadForComponent('event_card', 'summary_length', CivicthemeConstants::COMPONENT_SUMMARY_DEFAULT_LENGTH),
    ];

    $form['components']['navigation_card'] = [
      '#type' => 'details',
      '#title' => $this->t('Navigation card'),
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $form['components']['navigation_card']['summary_length'] = [
      '#title' => $this->t('Summary length'),
      '#description' => $this->t('Set the length of the Summary field: the content will be trimmed to this length and ellipsis will be added. Set to 0 for no limit.'),
      '#type' => 'number',
      '#required' => TRUE,
      '#min' => 0,
      '#default_value' => $this->themeConfigManager->loadForComponent('navigation_card', 'summary_length', CivicthemeConstants::COMPONENT_SUMMARY_DEFAULT_LENGTH),
    ];

    $form['components']['promo_card'] = [
      '#type' => 'details',
      '#title' => $this->t('Promo card'),
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $form['components']['promo_card']['summary_length'] = [
      '#title' => $this->t('Summary length'),
      '#description' => $this->t('Set the length of the Summary field: the content will be trimmed to this length and ellipsis will be added. Set to 0 for no limit.'),
      '#type' => 'number',
      '#required' => TRUE,
      '#min' => 0,
      '#default_value' => $this->themeConfigManager->loadForComponent('promo_card', 'summary_length', CivicthemeConstants::COMPONENT_SUMMARY_DEFAULT_LENGTH),
    ];

    $form['components']['publication_card'] = [
      '#type' => 'details',
      '#title' => $this->t('Publication card'),
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $form['components']['publication_card']['summary_length'] = [
      '#title' => $this->t('Summary length'),
      '#description' => $this->t('Set the length of the Summary field: the content will be trimmed to this length and ellipsis will be added. Set to 0 for no limit.'),
      '#type' => 'number',
      '#required' => TRUE,
      '#min' => 0,
      '#default_value' => $this->themeConfigManager->loadForComponent('publication_card', 'summary_length', CivicthemeConstants::COMPONENT_SUMMARY_DEFAULT_LENGTH),
    ];

    $form['components']['publication_card']['use_media_name'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use name of media'),
      '#description' => $this->t('Use name of media rather than file name on attachment component.'),
      '#default_value' => $this->themeConfigManager->loadForComponent('publication_card', 'use_media_name', TRUE),
    ];

    $form['components']['snippet'] = [
      '#type' => 'details',
      '#title' => $this->t('Snippet'),
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $form['components']['snippet']['summary_length'] = [
      '#title' => $this->t('Summary length'),
      '#description' => $this->t('Set the length of the Summary field: the content will be trimmed to this length and ellipsis will be added. Set to 0 for no limit.'),
      '#type' => 'number',
      '#required' => TRUE,
      '#min' => 0,
      '#default_value' => $this->themeConfigManager->loadForComponent('snippet', 'summary_length', CivicthemeConstants::COMPONENT_SUMMARY_DEFAULT_LENGTH),
    ];

    $form['components']['attachment'] = [
      '#type' => 'details',
      '#title' => $this->t('Attachment'),
      '#group' => 'components',
      '#tree' => TRUE,
    ];

    $form['components']['attachment']['use_media_name'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use name of media'),
      '#description' => $this->t('Use name of media rather than file name on attachment component.'),
      '#default_value' => $this->themeConfigManager->loadForComponent('attachment', 'use_media_name', TRUE),
    ];

    $form['#process'][] = $this->processForm(...);

    // Auto-discover per-component validation and submit handlers.
    foreach (array_keys($form['components']) as $component_name) {
      $validate = CivicthemeUtility::camelise('validate_' . $component_name);
      if (is_callable([$this, $validate])) {
        $form['#validate'][] = [$this, $validate];
      }

      $submit = CivicthemeUtility::camelise('submit_' . $component_name);
      if (is_callable([$this, $submit])) {
        $form['#submit'][] = [$this, $submit];
      }
    }
  }

  /**
   * Form process callback.
   *
   * @param array $element
   *   Form element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   *
   * @return array<string, mixed>
   *   Form element.
   */
  public function processForm(array &$element, FormStateInterface $form_state): array {
    // Vertical tabs do not work correctly with a form element with
    // '#tree' = TRUE. The active tab is set to the element children by JS,
    // so we have to explicitly add it to the values that should be cleaned.
    // @phpstan-ignore-next-line
    $form_state->addCleanValueKey(['components', 'components__active_tab']);

    return $element;
  }

  /**
   * Validate callback for theme settings form of Logo component.
   */
  public function validateLogo(array &$form, FormStateInterface $form_state): void {
    foreach (['primary', 'secondary'] as $logo_type) {
      foreach (civictheme_theme_options(TRUE) as $theme) {
        foreach (['desktop', 'mobile'] as $breakpoint) {
          $this->validateFileUpload(
            $form,
            $form_state,
            ['components', 'logo', $logo_type, $theme, $breakpoint, 'upload'],
            ['components', 'logo', $logo_type, $theme, $breakpoint, 'path']
          );
        }
      }
    }
  }

  /**
   * Validate callback for theme settings form to check footer settings.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   * @SuppressWarnings(PHPMD.ElseExpression)
   */
  public function validateFooter(array $form, FormStateInterface &$form_state): void {
    $this->validateFileUpload(
      $form,
      $form_state,
      ['components', 'footer', 'background_image', 'upload'],
      ['components', 'footer', 'background_image', 'path']
    );
  }

  /**
   * Validate callback for theme settings form of Link component.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function validateLink(array &$form, FormStateInterface $form_state): void {
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
   * Submit callback for theme settings form of Logo component.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  public function submitLogo(array &$form, FormStateInterface $form_state): void {
    foreach (['primary', 'secondary'] as $logo_type) {
      foreach (civictheme_theme_options(TRUE) as $theme) {
        foreach (['desktop', 'mobile'] as $breakpoint) {
          $this->submitFileUpload(
            $form,
            $form_state,
            ['components', 'logo', $logo_type, $theme, $breakpoint, 'upload'],
            ['components', 'logo', $logo_type, $theme, $breakpoint, 'path']
          );
        }
      }
    }
  }

  /**
   * Submit callback for theme settings form of Footer component.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  public function submitFooter(array &$form, FormStateInterface $form_state): void {
    $this->submitFileUpload(
      $form,
      $form_state,
      ['components', 'footer', 'background_image', 'upload'],
      ['components', 'footer', 'background_image', 'path']
    );
  }

  /**
   * Provide a description for a path field.
   *
   * @param string $filename
   *   The original path from the current field value.
   *
   * @return string
   *   Description string.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  protected function getPathFieldDescription(string $filename): string {
    $relative_file = $this->themeManager->getActiveTheme()->getPath() . '/' . $filename;
    $uploaded_file = $this->toFriendlyFilePath($this->getDefaultFileScheme() . '://' . $filename);

    return $this->t('Relative to Drupal web root: <code>@relative-file</code> or <code>@uploaded-file</code>', [
      '@relative-file' => $relative_file,
      '@uploaded-file' => $uploaded_file,
    ]);
  }

  /**
   * Normalize domain.
   */
  protected function externalLinkNormalizeDomain(string $domain): string {
    // Check that the part of the domain before first dot has protocol and
    // add one if it does not.
    if (!str_contains(substr($domain, 0, strpos($domain, '.') ?: 0), ':')) {
      $domain = 'https://' . (str_starts_with($domain, '//') ? str_replace('//', '', $domain) : $domain);
    }

    return $domain;
  }

  /**
   * Submit callback for site slogan component.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  public function submitSiteSlogan(array &$form, FormStateInterface $form_state): void {
    $slogan = $form_state->getValue(['components', 'site_slogan', 'content']);
    $this->themeConfigManager->save('components.site_slogan.content', $slogan);
  }

}
