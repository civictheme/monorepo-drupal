<?php

namespace Drupal\civictheme_migrate\Component;

use Drupal\paragraphs\Entity\Paragraph;

/**
 * Abstract class AbstractCivicThemeComponent.
 *
 * Represents a CivicTheme component.
 */
abstract class AbstractCivicThemeComponent extends AbstractComponent {

  /**
   * {@inheritdoc}
   */
  protected function build(): mixed {
    return self::createParagraph('civictheme_' . static::name(), $this->toArray(), 'field_c_p_', TRUE);
  }

  /**
   * Create paragraph from stub.
   */
  protected static function createParagraph($type, $stub, $field_prefix = '', $save = FALSE) {
    $paragraph = Paragraph::create([
      'type' => $type,
    ]);

    // Attaching all fields to paragraph.
    foreach ($stub as $field_name => $value) {
      $field_name = $field_prefix . $field_name;
      if ($paragraph->hasField($field_name)) {
        $paragraph->{$field_name} = $value;
      }
    }

    if ($save) {
      $paragraph->save();
    }

    return $paragraph;
  }

}
