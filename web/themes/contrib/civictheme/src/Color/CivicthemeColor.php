<?php

declare(strict_types=1);

namespace Drupal\civictheme\Color;

/**
 * CivicTheme color.
 *
 * Representation of the color value and formulas to produce it.
 */
class CivicthemeColor implements \Stringable {

  /**
   * The color value.
   *
   * @var string
   */
  protected $value;

  /**
   * The color formula.
   *
   * @var string
   */
  protected $formula;

  /**
   * The color source.
   *
   * Extracted from formula.
   *
   * @var string
   */
  protected $source;

  /**
   * The color filters.
   *
   * Extracted from formula.
   *
   * @var \Drupal\civictheme\Color\CivicthemeColorFilterBase[]
   */
  protected $filters = [];

  /**
   * Constructore.
   *
   * @param string $name
   *   The color name.
   * @param string $value
   *   The color value.
   * @param string $formula
   *   Optional color formula. Defaults to NULL meaning that the color is not
   *   produced from any other colors.
   */
  public function __construct(
    protected $name,
    $value,
    $formula = NULL) {
    if ($formula) {
      $this->setFormula($formula);
    }

    $this->setValue($value);
  }

  /**
   * Set color value.
   *
   * @param string $value
   *   The color value.
   * @param bool $apply_filters
   *   Flag to apply filters to the color. Usually set when a new color value
   *   gets calculated. Defaults to FALSE.
   *
   * @return $this
   *   Instance of the current class.
   *
   * @throws \Exception
   *   When value does not have a correct format and cannot be normalized.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   * @SuppressWarnings(PHPMD.MissingImport)
   * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
   */
  public function setValue($value, $apply_filters = FALSE): static {
    $val = CivicthemeColorUtility::keywordToHex($value);
    $val = CivicthemeColorUtility::normalizeHex($val);

    if ($apply_filters) {
      $val = $this->applyFilters($val);
    }

    $this->value = $val;

    return $this;
  }

  /**
   * Set color formula.
   *
   * The formula will be parsed into source and filters.
   *
   * @param string $formula
   *   The color formula to produce the color.
   *
   * @return $this
   *   Instance of the current class.
   */
  public function setFormula(string $formula): static {
    $parsed_formula = $this->parseFormula($formula);
    $this->formula = $formula;
    // @phpstan-ignore-next-line
    $this->source = $parsed_formula['source'];
    // @phpstan-ignore-next-line
    $this->filters = $parsed_formula['filters'];

    return $this;
  }

  /**
   * Get color name.
   *
   * @return string
   *   The color name.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Get color value.
   *
   * @param bool $with_hash
   *   Flag to include '#' symbol in front of the color value.
   *
   * @return string
   *   The color value, optionally prefixed by '#'.
   *
   * @SuppressWarnings(BooleanArgumentFlag)
   */
  public function getValue($with_hash = TRUE): string {
    return ($with_hash ? '#' : '') . ltrim($this->value, '#');
  }

  /**
   * Get color formula.
   *
   * @return string
   *   The color formula.
   */
  public function getFormula() {
    return $this->formula;
  }

  /**
   * Get color source.
   *
   * @return string
   *   The color source.
   */
  public function getSource() {
    return $this->source;
  }

  /**
   * Convert current class to string.
   *
   * @return string
   *   The color value.
   */
  public function __toString(): string {
    return $this->getValue();
  }

  /**
   * Parse color formula into source and filters.
   *
   * @param string $value
   *   The color value in the format (pipe '|' is used as a delimiter):
   *   source|filter,arg1,arg2...|filter,arg1,arg2
   *   - source - the name of the color
   *   - filter - the name of the supported color filter (tint, shade etc.)
   *   - arg1..argN - an argument to pass to the filter.
   *   Value is "piped" through filters.
   *
   * @return array<string, array<int<0, max>,\Drupal\civictheme\Color\CivicthemeColorFilterBase|null>|string>
   *   Array with parsed formula with keys:
   *   - source: (string) The name of the source color.
   *   - filters: (array) Array of initialised color filter objects.
   *
   * @SuppressWarnings(StaticAccess)
   */
  protected function parseFormula($value): array {
    $formula = [
      'filters' => [],
    ];

    $value = $value ?: '';

    $formula_parts = explode('|', $value);
    $formula['source'] = array_shift($formula_parts);

    foreach ($formula_parts as $formula_part) {
      $filter_parts = explode(',', $formula_part);
      $filter_name = array_shift($filter_parts);
      $formula['filters'][] = CivicthemeColorFilterBase::fromDefinition($filter_name, $filter_parts);
    }

    return $formula;
  }

  /**
   * Apply color filters.
   *
   * @param string $value
   *   Color value.
   *
   * @return string
   *   Color value after all filters were applied.
   */
  protected function applyFilters($value) {
    foreach ($this->filters as $filter) {
      $value = $filter->filter($value);
    }

    return $value;
  }

}
