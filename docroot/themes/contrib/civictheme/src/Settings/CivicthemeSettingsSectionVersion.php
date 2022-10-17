<?php

namespace Drupal\civictheme\Settings;

use Drupal\civictheme\CivicthemeVersionManager;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CivicTheme settings section to display a version.
 */
class CivicthemeSettingsSectionVersion extends CivicthemeSettingsAbstractSection {

  /**
   * Version manager.
   *
   * @var \Drupal\civictheme\CivicthemeVersionManager
   */
  protected $versionManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->setVersionManager($container->get('class_resolver')->getInstanceFromDefinition(CivicthemeVersionManager::class));

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function weight() {
    return 1;
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function form(&$form, FormStateInterface &$form_state) {
    $message = $this->t('<div class="messages messages--info">CivicTheme version: @version</div>', [
      '@version' => Link::fromTextAndUrl('dev', Url::fromUri($this->versionManager->source() . '/tree/develop'))->toString(),
    ]);

    if ($this->versionManager->version()) {
      $message = $this->t('<div class="messages messages--info">CivicTheme version: @version</div>', [
        '@version' => Link::fromTextAndUrl($this->versionManager->version(), Url::fromUri($this->versionManager->homepage() . '/releases/tag/' . $this->versionManager->version()))->toString(),
      ]);
    }

    $form['civictheme_version'] = [
      '#type' => 'inline_template',
      '#template' => '{{ content|raw }}',
      '#context' => [
        'content' => $message,
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
  public function setVersionManager(CivicthemeVersionManager $version_manager) {
    $this->versionManager = $version_manager;
  }

}
