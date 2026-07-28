<?php

declare(strict_types=1);

namespace Drupal\civictheme\Settings;

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\image\Entity\ImageStyle;

/**
 * CivicTheme settings section for Schema.org JSON-LD structured data.
 */
class CivicthemeSettingsFormSectionStructuredData extends CivicthemeSettingsFormSectionBase {

  /**
   * {@inheritdoc}
   */
  public function weight(): int {
    return 40;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, FormStateInterface $form_state): void {
    $form['structured_data'] = [
      '#type' => 'details',
      '#title' => $this->t('Structured data (JSON-LD)'),
      '#description' => $this->t('Emit Schema.org JSON-LD structured data in the page head of published content pages. Only the content types mapped to a Schema.org type below receive the markup.'),
      '#open' => FALSE,
      '#weight' => 70,
      '#tree' => TRUE,
    ];

    $form['structured_data']['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable JSON-LD structured data'),
      '#default_value' => $this->themeConfigManager->load('structured_data.enabled', FALSE),
    ];

    $visible_when_enabled = [
      'visible' => [
        ':input[name="structured_data[enabled]"]' => ['checked' => TRUE],
      ],
    ];

    $form['structured_data']['bundles'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Content type mapping'),
      '#description' => $this->t('Content types without a Schema.org type do not receive any markup.'),
      '#description_display' => 'before',
      '#states' => $visible_when_enabled,
      '#tree' => TRUE,
    ];

    $bundles = $this->entityTypeBundleInfo ? $this->entityTypeBundleInfo->getBundleInfo('node') : [];
    $mapping = $this->themeConfigManager->load('structured_data.bundles', []);
    $mapping = is_array($mapping) ? $mapping : [];
    $type_options = $this->typeOptions();

    foreach ($bundles as $bundle => $bundle_info) {
      $form['structured_data']['bundles'][$bundle] = [
        '#type' => 'select',
        '#title' => $bundle_info['label'] ?? $bundle,
        '#options' => $type_options,
        '#empty_option' => $this->t('- None -'),
        '#empty_value' => '',
        '#default_value' => $mapping[$bundle] ?? '',
      ];
    }

    $form['structured_data']['organization'] = [
      '#type' => 'details',
      '#title' => $this->t('Organization'),
      '#description' => $this->t('The publishing organisation, referenced as the publisher and the author of the mapped content. The organisation name is taken from the <em>Site name</em> in the basic site settings.'),
      '#open' => TRUE,
      '#states' => $visible_when_enabled,
      '#tree' => TRUE,
    ];

    $form['structured_data']['organization']['same_as'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Official profile URLs'),
      '#rows' => 4,
      '#description' => $this->t('Absolute URLs of the official pages and profiles of the organisation, one per line. Only include pages that the organisation controls, such as a government register record or its own social media profiles.'),
      '#element_validate' => [[self::class, 'validateSameAs']],
      '#default_value' => implode("\n", (array) $this->themeConfigManager->load('structured_data.organization.same_as', [])),
    ];

    $form['structured_data']['organization']['logo_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Organization logo path'),
      '#description' => $this->t('Path to the logo image relative to the Drupal root. Leave empty to use the Primary light desktop logo. Search engines expect a raster image (PNG, JPG or GIF), so a dedicated image is usually required as the CivicTheme default logo is an SVG.'),
      '#default_value' => $this->themeConfigManager->load('structured_data.organization.logo_path', ''),
    ];

    $form['structured_data']['image_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Image style'),
      '#description' => $this->t('Image style applied to the featured image of the content. Use <em>Original image</em> to reference the uploaded file.'),
      '#options' => $this->imageStyleOptions(),
      '#empty_option' => $this->t('Original image'),
      '#empty_value' => '',
      '#states' => $visible_when_enabled,
      '#default_value' => $this->themeConfigManager->load('structured_data.image_style', CivicthemeConstants::SOCIAL_SHARE_IMAGE_STYLE),
    ];

    $form['structured_data']['description_length'] = [
      '#type' => 'number',
      '#title' => $this->t('Description length'),
      '#description' => $this->t('Maximum length of the description sourced from the content summary.'),
      '#min' => 0,
      '#states' => $visible_when_enabled,
      '#default_value' => $this->themeConfigManager->load('structured_data.description_length', CivicthemeConstants::STRUCTURED_DATA_DESCRIPTION_LENGTH),
    ];
  }

  /**
   * Schema.org type options for the content type mapping.
   *
   * @return array<string, string>
   *   Type labels keyed by the setting value.
   */
  protected function typeOptions(): array {
    $options = [];

    foreach (_civictheme_structured_data_types() as $type => $definition) {
      $options[$type] = (string) ($definition['label'] ?? $type);
    }

    return $options;
  }

  /**
   * Image style options.
   *
   * @return array<string, string>
   *   Image style labels keyed by the image style name.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  protected function imageStyleOptions(): array {
    $options = [];

    foreach (ImageStyle::loadMultiple() as $name => $image_style) {
      $options[$name] = (string) $image_style->label();
    }

    return $options;
  }

  /**
   * Convert the value to an array and validate that all lines are URLs.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public static function validateSameAs(array $element, FormStateInterface $form_state): void {
    static::multilineToArray($element, $form_state);

    foreach ($form_state->getValue($element['#parents']) as $url) {
      if (!UrlHelper::isValid($url, TRUE)) {
        $form_state->setError($element, new TranslatableMarkup('The URL @url is not a valid absolute URL.', ['@url' => $url]));

        return;
      }
    }
  }

}
