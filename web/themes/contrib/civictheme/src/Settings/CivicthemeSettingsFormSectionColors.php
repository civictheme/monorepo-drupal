<?php

declare(strict_types=1);

namespace Drupal\civictheme\Settings;

use Drupal\civictheme\CivicthemeColorRecipeManager;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CivicTheme settings section for colours.
 *
 * Embeds the UI Kit colour picker (shipped with the theme in
 * dist/colour-picker) and stores its two artefacts in theme settings: the
 * recipe JSON (the durable source of the customisation) and the emitted
 * overrides CSS (the changed custom properties, loaded after the token
 * stylesheet). All colour computation happens in the picker's engine in the
 * browser - PHP only stores the strings.
 */
class CivicthemeSettingsFormSectionColors extends CivicthemeSettingsFormSectionBase {

  /**
   * The colour recipe manager.
   *
   * @var \Drupal\civictheme\CivicthemeColorRecipeManager
   */
  protected $colorRecipeManager;

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
    $instance->setColorRecipeManager($container->get('class_resolver')->getInstanceFromDefinition(CivicthemeColorRecipeManager::class));

    return $instance;
  }

  /**
   * Set colour recipe manager.
   */
  public function setColorRecipeManager(CivicthemeColorRecipeManager $color_recipe_manager): void {
    $this->colorRecipeManager = $color_recipe_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, FormStateInterface $form_state): void {
    $picker_url = '/' . $this->themeExtensionList->getPath('civictheme') . '/dist/colour-picker/ui/index.html';

    $form['colors'] = [
      '#type' => 'details',
      '#title' => $this->t('Colors'),
      '#weight' => 20,
      '#open' => TRUE,
      '#tree' => TRUE,
      '#description' => $this->t('<p>Website colours are defined by a <strong>recipe</strong>: your brand colours plus any token overrides and additions. Edit it in the colour picker below - the palette, per-component tokens and contrast checks update live - and save the form to apply it to the site. The same recipe file also drives the UI Kit build and Figma exports.</p>'),
    ];

    $form['colors']['use_color_selector'] = [
      '#title' => $this->t('Use Colour Picker'),
      '#description' => $this->t('Apply the colour recipe below to this site. When unchecked, the stock CivicTheme colours (or your own CSS) are used.'),
      '#type' => 'checkbox',
      '#default_value' => $this->themeConfigManager->load('colors.use_color_selector', FALSE),
    ];

    $form['colors']['picker'] = [
      '#type' => 'container',
      '#states' => [
        'visible' => [
          'input[name="colors[use_color_selector]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['colors']['picker']['frame'] = [
      '#type' => 'html_tag',
      '#tag' => 'iframe',
      '#attributes' => [
        'src' => $picker_url,
        'title' => $this->t('CivicTheme colour picker'),
        'class' => ['civictheme-colour-picker'],
        'data-civictheme-colour-picker' => 'true',
      ],
    ];

    $form['colors']['picker']['status'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => ['civictheme-colour-picker-status'],
        'data-civictheme-colour-picker-status' => 'true',
        'aria-live' => 'polite',
      ],
    ];

    $form['colors']['advanced'] = [
      '#type' => 'details',
      '#title' => $this->t('Recipe data'),
      '#open' => FALSE,
      '#description' => $this->t('These fields are kept in sync by the colour picker above. Without JavaScript, paste the artefacts produced by <code>npx @civictheme/colour-picker</code> (<code>recipe.json</code> and <code>civictheme-overrides.scss</code> content) directly.'),
    ];

    $form['colors']['advanced']['recipe'] = [
      '#title' => $this->t('Colour recipe (JSON)'),
      '#type' => 'textarea',
      '#rows' => 6,
      '#parents' => ['colors', 'recipe'],
      '#default_value' => $this->themeConfigManager->load('colors.recipe', ''),
      '#attributes' => [
        'data-civictheme-colour-recipe' => 'true',
        'class' => ['civictheme-colour-picker-recipe'],
      ],
    ];

    $form['colors']['advanced']['css'] = [
      '#title' => $this->t('Overrides CSS'),
      '#type' => 'textarea',
      '#rows' => 6,
      '#parents' => ['colors', 'css'],
      '#default_value' => $this->themeConfigManager->load('colors.css', ''),
      '#description' => $this->t('The changed custom properties emitted from the recipe. Loaded after the token stylesheet when the colour picker is enabled.'),
      '#attributes' => [
        'data-civictheme-colour-css' => 'true',
        'class' => ['civictheme-colour-picker-css'],
      ],
    ];

    $form['#validate'][] = $this->validateColors(...);
    $form['#submit'][] = $this->submitColors(...);
    $form['#attached']['library'][] = 'civictheme/theme-settings.colors';
  }

  /**
   * Validate callback for theme settings form of colours.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  public function validateColors(array &$form, FormStateInterface $form_state): void {
    $recipe = trim((string) $form_state->getValue(['colors', 'recipe'], ''));
    if ($recipe !== '') {
      $decoded = json_decode($recipe, TRUE);
      if (!is_array($decoded) || !array_key_exists('version', $decoded)) {
        $form_state->setErrorByName('colors][recipe', (string) $this->t('The colour recipe must be valid recipe JSON (an object with at least a "version" key).'));
      }
    }
  }

  /**
   * Submit callback for theme settings form of colours.
   *
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  public function submitColors(array &$form, FormStateInterface $form_state): void {
    // Invalidate caches only if changes were made.
    if (Json::encode($form_state->getValue('colors')) != Json::encode($this->themeConfigManager->load('colors'))) {
      $this->colorRecipeManager->invalidateCache();
      $this->messenger->addMessage($this->t('Colour picker cache was reset.'));
    }
  }

}
