<?php

declare(strict_types=1);

namespace Drupal\civictheme\Settings;

use Drupal\civictheme\CivicthemeVersionManager;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CivicTheme settings section to display a version.
 */
class CivicthemeSettingsFormSectionVersion extends CivicthemeSettingsFormSectionBase {

  /**
   * Version manager.
   *
   * @var \Drupal\civictheme\CivicthemeVersionManager
   */
  protected $versionManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    $instance = parent::create($container);
    $instance->setVersionManager($container->get('class_resolver')->getInstanceFromDefinition(CivicthemeVersionManager::class));

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function weight(): int {
    return 10;
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function form(array &$form, FormStateInterface $form_state): void {
    $version_uri = $this->versionManager->source() . '/tree/develop';

    $version = $this->versionManager->version();
    if ($version != CivicthemeVersionManager::DEFAULT_VERSION) {
      $version_uri = $this->versionManager->homepage() . '/releases/tag/' . $version;
    }

    $form['civictheme_version'] = [
      '#type' => 'inline_template',
      '#template' => '{{ content|raw }}',
      '#context' => [
        'content' => $this->t('<div class="messages messages--info">Your CivicTheme version: @version</div>', [
          '@version' => Link::fromTextAndUrl($version, Url::fromUri($version_uri))->toString(),
        ]),
      ],
      '#weight' => -100,
    ];
  }

  /**
   * Set version manager.
   *
   * @param \Drupal\civictheme\CivicthemeVersionManager $version_manager
   *   Version manager.
   */
  public function setVersionManager(CivicthemeVersionManager $version_manager): void {
    $this->versionManager = $version_manager;
  }

}
