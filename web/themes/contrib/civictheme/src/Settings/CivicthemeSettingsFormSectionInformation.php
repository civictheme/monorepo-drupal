<?php

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
  public function weight() {
    return 20;
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function form(&$form, FormStateInterface &$form_state) {
    $message = $this->t('<div class="messages messages--info">@repository<br/>@design_system<br/>@documentation<br/>@issues</div>', [
      '@repository' => Link::fromTextAndUrl('Code repository (GitHub)', Url::fromUri('https://github.com/salsadigitalauorg/civictheme'))->toString(),
      '@design_system' => Link::fromTextAndUrl('Design system (Figma)', Url::fromUri('https://www.civictheme.io/figma'))->toString(),
      '@documentation' => Link::fromTextAndUrl('Documentation', Url::fromUri('https://www.civictheme.io/getting-started/developers'))->toString(),
      '@issues' => Link::fromTextAndUrl('Report issues', Url::fromUri('https://github.com/salsadigitalauorg/civictheme_source/issues'))->toString(),
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
