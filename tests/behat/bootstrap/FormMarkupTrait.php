<?php

/**
 * @file
 * Form markup assertion steps for CivicTheme form rendering contract tests.
 */

declare(strict_types=1);

use Behat\Mink\Element\NodeElement;

/**
 * Markup-level assertions for form element rendering.
 *
 * Supports the form rendering contract tests guarding the form element
 * refactor: attribute values, label
 * association and ordering, fieldset structure and raw page markup patterns.
 */
trait FormMarkupTrait {

  /**
   * Assert that an element has an attribute with an exact value.
   *
   * @Then the element :selector should have a(n) :attribute attribute equal to :value
   */
  public function formMarkupAssertAttributeEquals(string $selector, string $attribute, string $value): void {
    $element = $this->formMarkupFindElement($selector);
    $actual = $element->getAttribute($attribute);

    if ($actual === NULL) {
      throw new \Exception(sprintf('The "%s" attribute was not found on the element "%s" on the page %s.', $attribute, $selector, $this->getSession()->getCurrentUrl()));
    }

    if ($actual !== $value) {
      throw new \Exception(sprintf('The "%s" attribute of the element "%s" is "%s", expected "%s".', $attribute, $selector, $actual, $value));
    }
  }

  /**
   * Assert that an element has an attribute with any value.
   *
   * @Then the element :selector should have a(n) :attribute attribute
   */
  public function formMarkupAssertAttributeExists(string $selector, string $attribute): void {
    $element = $this->formMarkupFindElement($selector);

    if (!$element->hasAttribute($attribute)) {
      throw new \Exception(sprintf('The "%s" attribute was not found on the element "%s" on the page %s.', $attribute, $selector, $this->getSession()->getCurrentUrl()));
    }
  }

  /**
   * Assert that no element matching the selector has the attribute.
   *
   * @Then the element :selector should not have a(n) :attribute attribute
   */
  public function formMarkupAssertNoAttribute(string $selector, string $attribute): void {
    $elements = $this->getSession()->getPage()->findAll('css', $selector);

    if (empty($elements)) {
      throw new \Exception(sprintf('The "%s" element was not found on the page %s.', $selector, $this->getSession()->getCurrentUrl()));
    }

    foreach ($elements as $element) {
      if ($element->hasAttribute($attribute)) {
        throw new \Exception(sprintf('An element matching "%s" has the "%s" attribute with value "%s", but it should not be present.', $selector, $attribute, (string) $element->getAttribute($attribute)));
      }
    }
  }

  /**
   * Assert that a non-empty label linked via "for" renders before the field.
   *
   * @Then the field with id :id should have an associated label displayed before it
   */
  public function formMarkupAssertLabelBefore(string $id): void {
    $this->formMarkupAssertLabelPosition($id, 'before');
  }

  /**
   * Assert that a non-empty label linked via "for" renders after the field.
   *
   * @Then the field with id :id should have an associated label displayed after it
   */
  public function formMarkupAssertLabelAfter(string $id): void {
    $this->formMarkupAssertLabelPosition($id, 'after');
  }

  /**
   * Assert that the first element child of an element is a legend.
   *
   * @Then the element :selector should contain a legend as its first child
   */
  public function formMarkupAssertLegendFirstChild(string $selector): void {
    $element = $this->formMarkupFindElement($selector);

    if (!preg_match('~<\s*([a-z][a-z0-9-]*)~i', $element->getHtml(), $matches)) {
      throw new \Exception(sprintf('The element "%s" has no element children.', $selector));
    }

    if (strtolower($matches[1]) !== 'legend') {
      throw new \Exception(sprintf('The first child of the element "%s" is "%s", expected "legend".', $selector, strtolower($matches[1])));
    }
  }

  /**
   * Assert that the page markup matches a regular expression.
   *
   * The pattern is used without delimiters and must not contain "~".
   *
   * @Then the page markup should match :pattern
   */
  public function formMarkupAssertMatches(string $pattern): void {
    $pattern = $this->fixStepArgument($pattern);
    if (!preg_match('~' . $pattern . '~', $this->getSession()->getDriver()->getContent())) {
      throw new \Exception(sprintf('The page markup does not match the pattern "%s" on the page %s.', $pattern, $this->getSession()->getCurrentUrl()));
    }
  }

  /**
   * Assert that the page markup does not match a regular expression.
   *
   * The pattern is used without delimiters and must not contain "~".
   *
   * @Then the page markup should not match :pattern
   */
  public function formMarkupAssertNotMatches(string $pattern): void {
    $pattern = $this->fixStepArgument($pattern);
    if (preg_match('~' . $pattern . '~', $this->getSession()->getDriver()->getContent(), $matches)) {
      throw new \Exception(sprintf('The page markup matches the pattern "%s" ("%s") on the page %s but should not.', $pattern, $matches[0], $this->getSession()->getCurrentUrl()));
    }
  }

  /**
   * Assert that an attribute references only ids of existing elements.
   *
   * Supports space-separated id lists as used by aria-describedby.
   *
   * @Then the :attribute attribute of the element :selector should reference the id of an existing element
   */
  public function formMarkupAssertAttributeReferencesId(string $attribute, string $selector): void {
    $element = $this->formMarkupFindElement($selector);
    $value = $element->getAttribute($attribute);

    if ($value === NULL || trim($value) === '') {
      throw new \Exception(sprintf('The "%s" attribute was not found or is empty on the element "%s".', $attribute, $selector));
    }

    foreach (preg_split('/\s+/', trim($value)) as $id) {
      $target = $this->getSession()->getPage()->find('xpath', sprintf("//*[@id='%s']", $id));
      if ($target === NULL) {
        throw new \Exception(sprintf('The "%s" attribute of the element "%s" references the id "%s", but no element with that id exists.', $attribute, $selector, $id));
      }
    }
  }

  /**
   * Assert label existence, non-empty text and document order for a field.
   */
  protected function formMarkupAssertLabelPosition(string $id, string $position): void {
    $label = $this->getSession()->getPage()->find('css', sprintf('label[for="%s"]', $id));

    if ($label === NULL) {
      throw new \Exception(sprintf('No label with for="%s" was found on the page %s.', $id, $this->getSession()->getCurrentUrl()));
    }

    if (trim($label->getText()) === '') {
      throw new \Exception(sprintf('The label for "%s" is empty.', $id));
    }

    $content = $this->getSession()->getDriver()->getContent();
    $id_quoted = preg_quote($id, '~');

    if (!preg_match('~<label[^>]*\bfor="' . $id_quoted . '"~', $content, $label_match, PREG_OFFSET_CAPTURE)) {
      throw new \Exception(sprintf('Unable to locate the label for "%s" in the page markup.', $id));
    }

    if (!preg_match('~<(?:input|select|textarea|button)[^>]*\bid="' . $id_quoted . '"~', $content, $field_match, PREG_OFFSET_CAPTURE)) {
      throw new \Exception(sprintf('Unable to locate a form control with id "%s" in the page markup.', $id));
    }

    $label_offset = $label_match[0][1];
    $field_offset = $field_match[0][1];

    if ($position === 'before' && $label_offset > $field_offset) {
      throw new \Exception(sprintf('The label for "%s" renders after the control but should render before it.', $id));
    }

    if ($position === 'after' && $label_offset < $field_offset) {
      throw new \Exception(sprintf('The label for "%s" renders before the control but should render after it.', $id));
    }
  }

  /**
   * Find the first element matching a CSS selector or fail.
   */
  protected function formMarkupFindElement(string $selector): NodeElement {
    $element = $this->getSession()->getPage()->find('css', $selector);

    if ($element === NULL) {
      throw new \Exception(sprintf('The "%s" element was not found on the page %s.', $selector, $this->getSession()->getCurrentUrl()));
    }

    return $element;
  }

}
