<?php

declare(strict_types=1);

namespace Drupal\civictheme;

use Drupal\civictheme\Settings\CivicthemeSettingsFormSectionBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Extension\ThemeExtensionList;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CivicTheme settings form manager.
 */
final class CivicthemeSettingsFormManager implements ContainerInjectionInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct(
    protected CivicthemePluginLoader $pluginLoader,
    protected ThemeExtensionList $themeExtensionList
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('class_resolver')->getInstanceFromDefinition(CivicthemePluginLoader::class),
      $container->get('extension.list.theme')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, FormStateInterface $form_state): void {
    /** @var \Drupal\civictheme\Settings\CivicthemeSettingsFormSectionBase[] $sections */
    $sections = $this->pluginLoader->load(
      $this->themeExtensionList->get('civictheme')->getPath() . '/src/Settings',
      CivicthemeSettingsFormSectionBase::class
    );

    // Sort by weight.
    usort($sections, static function ($a, $b): int {
      return strnatcasecmp((string) $a->weight(), (string) $b->weight());
    });

    foreach ($sections as $section) {
      $section->form($form, $form_state);
    }
  }

}
