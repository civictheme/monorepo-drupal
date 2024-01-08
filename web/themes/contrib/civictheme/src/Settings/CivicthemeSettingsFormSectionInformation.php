<?php

declare(strict_types=1);

namespace Drupal\civictheme\Settings;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * CivicTheme settings section to display additional information.
 */
class CivicthemeSettingsFormSectionInformation extends CivicthemeSettingsFormSectionBase {

  /**
   * {@inheritdoc}
   */
  public function weight(): int {
    return 20;
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function form(array &$form, FormStateInterface $form_state): void {
    $message = $this->t('<div class="messages messages--info">@documentation<br/>@design_system<br/>@repository<br/>@issues</div>', [
      '@documentation' => Link::fromTextAndUrl('Documentation', Url::fromUri('https://docs.civictheme.io/'))->toString(),
      '@design_system' => Link::fromTextAndUrl('Design system (Figma)', Url::fromUri('https://www.civictheme.io/figma'))->toString(),
      '@repository' => Link::fromTextAndUrl('Code repository (GitHub)', Url::fromUri('https://github.com/civictheme/civictheme'))->toString(),
      '@issues' => Link::fromTextAndUrl('Report issues', Url::fromUri('https://github.com/civictheme/monorepo-drupal/issues'))->toString(),
    ]);

    $form['civictheme_information'] = [
      '#type' => 'inline_template',
      '#template' => '{{ content|raw }}',
      '#context' => [
        'content' => $message,
      ],
      '#weight' => -100,
    ];
  }

}
