<?php

namespace Drupal\civictheme\Settings;

use Drupal\Core\Form\FormStateInterface;

/**
 * CivicTheme settings section to provide Storybook.
 */
class CivicthemeSettingsFormSectionStorybook extends CivicthemeSettingsFormSectionBase {

  /**
   * {@inheritdoc}
   */
  public function weight() {
    return 5;
  }

  /**
   * {@inheritdoc}
   */
  public function form(&$form, FormStateInterface &$form_state) {
    $theme_name = $this->themeManager->getActiveTheme()->getName();
    $theme_path = $this->themeExtensionList->getPath($theme_name);

    // Show compiled Storybook.
    // @note For development of components, please use `npm run storybook`.
    $form['storybook'] = [
      '#type' => 'details',
      '#title' => $this->t('Storybook for %theme theme', [
        '%theme' => $theme_name,
      ]),
      '#open' => FALSE,
      '#weight' => 52,
    ];

    $storybook_file = $theme_path . '/storybook-static/index.html';

    if (file_exists($storybook_file)) {
      $url = \Drupal::service('file_url_generator')->generateAbsoluteString($storybook_file) . '?cachebust=' . time();
      $form['storybook']['link'] = [
        '#type' => 'inline_template',
        '#template' => '<p><a href="{{ url }}">{{ url }}</a></p>',
        '#context' => [
          'url' => $url,
        ],
      ];

      $form['storybook']['markup'] = [
        '#type' => 'inline_template',
        '#template' => '<iframe id="storybook" width="100%" height="1024" src="{{ url }}"></iframe>',
        '#context' => [
          'url' => $url,
        ],
      ];

      return;
    }

    $form['storybook']['markup'] = [
      '#markup' => $this->t('Compiled Storybook cannot be found at @path. Try compiling it with <code>npm run build</code>.', [
        '@path' => $storybook_file,
      ]),
    ];
  }

}
