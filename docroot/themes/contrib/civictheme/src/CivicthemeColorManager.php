<?php

namespace Drupal\civictheme;

use Drupal\civictheme\Color\CivicthemeColor;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CivicTheme color manager.
 *
 * Responsible for all operations with colors.
 *
 * @SuppressWarnings(ExcessiveClassComplexity)
 */
class CivicthemeColorManager implements ContainerInjectionInterface {

  /**
   * Defines color type 'brand'.
   */
  const COLOR_TYPE_BRAND = 'brand';

  /**
   * Defines color type 'palette'.
   */
  const COLOR_TYPE_PALETTE = 'palette';

  /**
   * Defines default color for cases when the color value is not set.
   */
  const COLOR_DEFAULT = '#CCCCCC';

  /**
   * Defines CSS variable prefix used when working with stylesheets.
   */
  const CSS_VARIABLES_PREFIX = 'ct-color';

  /**
   * The color matrix.
   *
   * Using 'matrix' instead of 'colors' to avoid confusion between a structure
   * of colors and the color values.
   *
   * @var array
   */
  protected $matrix;

  /**
   * The path to the CSS file.
   *
   * @var string
   */
  protected $cssColorsFilePath;

  /**
   * The plugin manager.
   *
   * @var \Drupal\civictheme\CivicthemePluginLoader
   */
  protected $pluginLoader;

  /**
   * The config manager.
   *
   * @var \Drupal\civictheme\CivicthemeConfigManager
   */
  protected $configManager;

  /**
   * The stylesheet parser.
   *
   * @var \Drupal\civictheme\CivicthemeStylesheetParser
   */
  protected $stylesheetParser;

  /**
   * The stylesheet generator.
   *
   * @var \Drupal\civictheme\CivicthemeStylesheetGenerator
   */
  protected $stylesheetGenerator;

  /**
   * Constructor.
   */
  public function __construct(CivicthemePluginLoader $plugin_loader, CivicthemeConfigManager $config_manager, CivicthemeStylesheetParser $stylesheet_parser, CivicthemeStylesheetGenerator $stylesheet_generator) {
    $this->pluginLoader = $plugin_loader;
    $this->configManager = $config_manager;
    $this->stylesheetParser = $stylesheet_parser;
    $this->stylesheetGenerator = $stylesheet_generator;
    $this->pluginLoader->load(__DIR__ . '/Color');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('class_resolver')->getInstanceFromDefinition(CivicthemePluginLoader::class),
      $container->get('class_resolver')->getInstanceFromDefinition(CivicthemeConfigManager::class),
      $container->get('class_resolver')->getInstanceFromDefinition(CivicthemeStylesheetParser::class),
      $container->get('class_resolver')->getInstanceFromDefinition(CivicthemeStylesheetGenerator::class)
    );
  }

  /**
   * A map of the Palette colors.
   *
   * @param bool $flatten_groups
   *   Flag to flatten color croups.
   *
   * @return array|array[]
   *   Array of colors divided in groups. The values are color formulas or FALSE
   *   if the color does not depend on any other colors.
   *
   * @SuppressWarnings(BooleanArgumentFlag)
   */
  public static function colorPaletteMap($flatten_groups = FALSE) {
    $map = [
      CivicthemeConstants::THEME_LIGHT => [
        'typography' => [
          'heading' => 'brand1|shade,60',
          'body' => 'brand1|shade,80|tint,20',
        ],
        'background' => [
          'background-light' => 'brand2|tint,90',
          'background' => 'brand2',
          'background-dark' => 'brand2|shade,20',
        ],
        'border' => [
          'border-light' => 'brand2|shade,25',
          'border' => 'brand2|shade,60',
          'border-dark' => 'brand2|shade,90',
        ],
        'interaction' => [
          'interaction-text' => 'brand2|tint,80',
          'interaction-background' => 'brand1',
          'interaction-hover-text' => 'brand2|tint,80',
          'interaction-hover-background' => 'brand1|shade,40',
          'interaction-focus' => FALSE,
        ],
        'highlight' => [
          'highlight' => 'brand3',
        ],
        'status' => [
          'information' => FALSE,
          'warning' => FALSE,
          'error' => FALSE,
          'success' => FALSE,
        ],
      ],
      CivicthemeConstants::THEME_DARK => [
        'typography' => [
          'heading' => 'brand1|tint,95',
          'body' => 'brand1|tint,85',
        ],
        'background' => [
          'background-light' => 'brand2|tint,5',
          'background' => 'brand2',
          'background-dark' => 'brand2|shade,30',
        ],
        'border' => [
          'border-light' => 'brand2|tint,65',
          'border' => 'brand2|tint,10',
          'border-dark' => 'brand2|shade,30',
        ],
        'interaction' => [
          'interaction-text' => 'brand2',
          'interaction-background' => 'brand1',
          'interaction-hover-text' => 'brand2|shade,30',
          'interaction-hover-background' => 'brand1|tint,40',
          'interaction-focus' => FALSE,
        ],
        'highlight' => [
          'highlight' => 'brand3',
        ],
        'status' => [
          'information' => FALSE,
          'warning' => FALSE,
          'error' => FALSE,
          'success' => FALSE,
        ],
      ],
    ];

    if ($flatten_groups) {
      $new_map = [];
      foreach ($map as $theme => $group) {
        foreach ($group as $value) {
          foreach ($value as $k => $v) {
            $new_map[$theme][$k] = $v;
          }
        }
      }
      $map = $new_map;
    }

    return $map;
  }

  /**
   * Get colors, optionally filtered by type and theme.
   *
   * @param string $type
   *   Optional color type.
   * @param string $theme
   *   Optional color theme.
   *
   * @return array|array[]|\array[][]|mixed
   *   If $type and $theme not provided - returns color matrix keyed by type and
   *   theme with CivicthemeColor objects as values.
   *   If only $type is provided - returns the color matrix for that type.
   *   If $type and $theme as provided - returns the color matrix for that type
   *   and theme.
   */
  public function getColors($type = NULL, $theme = NULL) {
    if (empty($this->matrix)) {
      $this->initMatrix();
    }

    if ($type) {
      self::validateType($type);
    }
    if ($theme) {
      self::validateTheme($theme);
    }

    return $type ? ($theme ? $this->matrix[$type][$theme] ?? [] : $this->matrix[$type] ?? self::defaultMatrix()[$type]) : $this->matrix ?? self::defaultMatrix();
  }

  /**
   * Get color as strings.
   *
   * @return array
   *   Color matrix keyed by type and theme with colors as values.
   */
  public function getColorsStrings() {
    $result = [];

    foreach ($this->getColors() as $type => $theme) {
      foreach ($theme as $theme_name => $colors) {
        foreach ($colors as $color_name => $color) {
          $result[$type][$theme_name][$color_name] = (string) $color;
        }
      }
    }

    return $result;
  }

  /**
   * Set colors from the provided matrix.
   *
   * @param array $color_matrix
   *   The color matrix in the expected format.
   *
   * @return $this
   *   Instance of the current class.
   */
  public function setColors(array $color_matrix) {
    self::validateMatrixStructure($color_matrix);

    foreach ($color_matrix as $type => $theme_color) {
      foreach ($theme_color as $theme => $color) {
        foreach ($color as $name => $value) {
          $this->setMatrixColor($type, $theme, $name, $value);
        }
      }
    }

    return $this;
  }

  /**
   * Return existing or generate a new stylesheet.
   *
   * @param string $file_suffix
   *   Optional file suffix for the generated stylesheet. Defaults to 'default'.
   *   Usually provided to separate stylesheets produced in multiple contexts.
   *
   * @return string
   *   URI of the stylesheet file.
   */
  public function stylesheet($file_suffix = 'default') {
    return $this->stylesheetGenerator
      ->setStylesheetUriSuffix($file_suffix)
      ->generate($this->getColors(self::COLOR_TYPE_PALETTE), ['html'], self::CSS_VARIABLES_PREFIX);
  }

  /**
   * Set the file path of the existing CSS file.
   *
   * @param string $path
   *   The file path.
   *
   * @return $this
   *   Instance of the current class.
   */
  public function setCssColorsFilePath($path) {
    if (is_readable($path)) {
      $this->cssColorsFilePath = $path;
    }

    return $this;
  }

  /**
   * Save color matrix.
   *
   * @param bool $invalidate_caches
   *   Optional flag to invalidate caches after save. Defaults to FALSE.
   *
   * @return $this
   *   Instance of the current class.
   *
   * @SuppressWarnings(BooleanArgumentFlag)
   */
  public function save($invalidate_caches = FALSE) {
    $this->saveMatrixToConfig();

    if ($invalidate_caches) {
      $this->invalidateCache();
    }

    return $this;
  }

  /**
   * Invalidate cache.
   *
   * @return $this
   *   Instance of the current class.
   */
  public function invalidateCache() {
    $this->stylesheetGenerator->purge();

    drupal_flush_all_caches();

    return $this;
  }

  /**
   * Load color matrix from the CSS file.
   *
   * @return array|\array[][]
   *   The color matrix.
   */
  protected function matrixFromCss() {
    $variables = $this->stylesheetParser
      ->setCssVariablePrefix(self::CSS_VARIABLES_PREFIX)
      ->setContent($this->loadCssVariablesContent())
      ->variables();

    $colors = self::defaultMatrix();
    foreach ($variables as $key => $value) {
      $key = str_replace('--' . self::CSS_VARIABLES_PREFIX . CivicthemeStylesheetParser::CSS_VARIABLES_SEPARATOR, '', $key);
      $parts = explode(CivicthemeStylesheetParser::CSS_VARIABLES_SEPARATOR, $key);
      $theme = array_shift($parts);
      try {
        self::validateTheme($theme);
      }
      catch (\Exception $exception) {
        continue;
      }
      $colors[self::COLOR_TYPE_PALETTE][$theme][implode(CivicthemeStylesheetParser::CSS_VARIABLES_SEPARATOR, $parts)] = $value;
    }

    self::validateMatrixStructure($colors);

    return $colors;
  }

  /**
   * Load content from CSS variables file.
   *
   * @return string
   *   Loaded content or FALSE if the file is not readable.
   */
  protected function loadCssVariablesContent() {
    if (!empty($this->cssColorsFilePath) && is_readable($this->cssColorsFilePath)) {
      return file_get_contents($this->cssColorsFilePath);
    }

    return FALSE;
  }

  /**
   * Initialise color matrix.
   *
   * @return $this
   *   Instance of the current class.
   */
  protected function initMatrix() {
    $palette_matrix = [self::COLOR_TYPE_PALETTE => self::colorPaletteMap(TRUE)] + self::defaultMatrix();
    $config_matrix = $this->loadMatrixFromConfig();
    $styles_matrix = $this->matrixFromCss();
    foreach ($palette_matrix as $type => $theme_colors) {
      foreach ($theme_colors as $theme => $color) {
        foreach ($color as $name => $formula) {
          $this->matrix[$type][$theme][$name] = new CivicthemeColor(
            $name,
            // Config or styles or default color.
            $config_matrix[$type][$theme][$name] ?? $styles_matrix[$type][$theme][$name] ?? self::COLOR_DEFAULT,
            $formula
          );
        }
      }
    }

    return $this;
  }

  /**
   * Populate matrix with a single color value.
   *
   * @param string $type
   *   The color type.
   * @param string $theme
   *   The color theme.
   * @param string $name
   *   The color name.
   * @param string $value
   *   The color value.
   *
   * @return $this
   *   Instance of the current class.
   */
  protected function setMatrixColor($type, $theme, $name, $value) {
    self::validateType($type);
    self::validateTheme($theme);

    if ($type == self::COLOR_TYPE_BRAND) {
      $dependents = $this->getColorDependencies($theme, $name);
      foreach ($dependents as $dependent) {
        $dependent->setValue($value);
      }
    }

    $this->matrix[$type][$theme][$name] = $this->matrix[$type][$theme][$name] ?? new CivicthemeColor($name, $value ?? self::COLOR_DEFAULT);
    $this->matrix[$type][$theme][$name]->setValue($value);

    return $this;
  }

  /**
   * Get an array of the color dependecies.
   *
   * @return \Drupal\civictheme\Color\CivicthemeColor[]
   *   Array of color dependencies.
   */
  protected function getColorDependencies($theme, $name) {
    $dependencies = [];
    /** @var \Drupal\civictheme\Color\CivicthemeColor $color */
    foreach ($this->getColors(self::COLOR_TYPE_PALETTE, $theme) as $color) {
      if ($color->getSource() == $name) {
        $dependencies[] = $color;
      }
    }

    return $dependencies;
  }

  /**
   * Load matrix from the config.
   *
   * @return array|\array[][]|mixed
   *   The color matrix in the expected format.
   */
  protected function loadMatrixFromConfig() {
    $colors = $this->configManager->load('colors') ?? self::defaultMatrix();
    unset($colors['use_brand_colors']);
    self::validateMatrixStructure($colors);

    return $colors;
  }

  /**
   * Save matrix to the config.
   *
   * @return $this
   *   Instance of the current class.
   */
  protected function saveMatrixToConfig() {
    $matrix = $this->getColorsStrings();

    $use_brand_colors = FALSE;
    if (
      !empty($matrix[self::COLOR_TYPE_BRAND][CivicthemeConstants::THEME_LIGHT]) &&
      !empty($matrix[self::COLOR_TYPE_BRAND][CivicthemeConstants::THEME_DARK])
    ) {
      $use_brand_colors = TRUE;
    }

    $this->configManager->save('colors', ['use_brand_colors' => $use_brand_colors] + $matrix);

    return $this;
  }

  /**
   * Validate color matrix structure.
   *
   * @param array $matrix
   *   The matrix.
   *
   * @throws \Exception
   *   When provided matrix structure does not have an expected structure.
   *
   * @SuppressWarnings(MissingImport)
   */
  protected static function validateMatrixStructure(array $matrix) {
    if (!is_array($matrix) || count($matrix) != 2) {
      throw new \Exception(sprintf('Invalid color matrix structure: should be an array with exactly 2 elements keyed by "%s" and "%s"', self::COLOR_TYPE_BRAND, self::COLOR_TYPE_PALETTE));
    }

    if (!array_key_exists(self::COLOR_TYPE_BRAND, $matrix) || !array_key_exists(self::COLOR_TYPE_PALETTE, $matrix)) {
      throw new \Exception(sprintf('Invalid color matrix structure: top-level keys should be "%s" or "%s"', self::COLOR_TYPE_BRAND, self::COLOR_TYPE_PALETTE));
    }
  }

  /**
   * Validate color type.
   *
   * @param string $type
   *   The color type.
   *
   * @throws \Exception
   *   When provided color type is not one of the expected types.
   *
   * @SuppressWarnings(MissingImport)
   */
  protected static function validateType($type) {
    if (!\in_array($type, [self::COLOR_TYPE_BRAND, self::COLOR_TYPE_PALETTE])) {
      throw new \Exception('Invalid color type');
    }
  }

  /**
   * Validate color theme.
   *
   * @param string $theme
   *   The color theme.
   *
   * @throws \Exception
   *   When provided color theme is not one of the expected themes.
   *
   * @SuppressWarnings(MissingImport)
   */
  protected static function validateTheme($theme) {
    if (!in_array($theme, [
      CivicthemeConstants::THEME_LIGHT,
      CivicthemeConstants::THEME_DARK,
    ])) {
      throw new \Exception('Invalid color theme');
    }
  }

  /**
   * Default matrix structure.
   */
  protected static function defaultMatrix() {
    return [
      static::COLOR_TYPE_BRAND => [
        CivicthemeConstants::THEME_LIGHT => [],
        CivicthemeConstants::THEME_DARK => [],
      ],
      static::COLOR_TYPE_PALETTE => [
        CivicthemeConstants::THEME_LIGHT => [],
        CivicthemeConstants::THEME_DARK => [],
      ],
    ];
  }

}
