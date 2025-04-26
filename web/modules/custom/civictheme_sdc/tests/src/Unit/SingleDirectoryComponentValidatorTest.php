<?php

namespace Drupal\Tests\civictheme_sdc\Unit;

use Drupal\Core\Plugin\Component;
use Drupal\Core\Render\Component\Exception\InvalidComponentException;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Theme\Component\ComponentValidator;
use Symfony\Component\Yaml\Yaml;
use PHPUnit\Framework\TestCase;

/**
 * Tests for component validation.
 */
class SingleDirectoryComponentValidatorTest extends TestCase {

  /**
   * Tests that valid component definitions don't cause errors.
   *
   * @dataProvider dataProviderValidateDefinitionValid
   *
   * @throws \Drupal\Core\Render\Component\Exception\InvalidComponentException
   */
  public function testValidateDefinitionValid(array $definition): void {
    $component_validator = new ComponentValidator();
    $component_validator->setValidator();
    $this->assertTrue(
      $component_validator->validateDefinition($definition, TRUE),
      'The invalid component definition did not throw an error.'
    );
  }

  /**
   * Data provider with valid component definitions.
   *
   * @return array<int, array<int,array<string>>>
   *   The data.
   */
  public static function dataProviderValidateDefinitionValid(): array {
    return [
      array_map(
        [static::class, 'loadComponentDefinitionFromFs'],
        ['my-banner', 'my-button', 'my-cta'],
      ),
    ];
  }

  /**
   * Tests invalid component definitions.
   *
   * @dataProvider dataProviderValidateDefinitionInvalid
   */
  public function testValidateDefinitionInvalid(array $definition): void {
    $this->expectException(InvalidComponentException::class);
    $component_validator = new ComponentValidator();
    $component_validator->setValidator();
    $component_validator->validateDefinition($definition, TRUE);
  }

  /**
   * Data provider with invalid component definitions.
   *
   * @return \Generator
   *   Returns the generator with the invalid definitions.
   */
  public static function dataProviderValidateDefinitionInvalid(): \Generator {
    $valid_cta = static::loadComponentDefinitionFromFs('my-cta');

    $cta_with_missing_required = $valid_cta;
    unset($cta_with_missing_required['path']);
    yield 'missing required' => [$cta_with_missing_required];

    $cta_with_invalid_class = $valid_cta;
    $cta_with_invalid_class['props']['properties']['attributes']['type'] = 'Drupal\Foo\Invalid';
    yield 'invalid class' => [$cta_with_invalid_class];

    $cta_with_invalid_enum = array_merge(
      $valid_cta,
      ['extension_type' => 'invalid'],
    );
    yield 'invalid enum' => [$cta_with_invalid_enum];

    // A list of property types that are not strings, but can be provided via
    // YAML.
    $non_string_types = [NULL, 123, 123.45, TRUE];
    foreach ($non_string_types as $non_string_type) {
      $cta_with_non_string_prop_type = $valid_cta;
      $cta_with_non_string_prop_type['props']['properties']['text']['type'] = $non_string_type;
      yield sprintf('non string type (%s)', $non_string_type) => [$cta_with_non_string_prop_type];
      // Same, but as a part of the list of allowed types.
      $cta_with_non_string_prop_type['props']['properties']['text']['type'] = [
        'string',
        $non_string_type,
      ];
      yield sprintf('non string type (%s) in a list of types', $non_string_type) => [$cta_with_non_string_prop_type];
    }

    // The array is a valid value for the 'type' parameter, but it is not
    // allowed as the allowed type.
    $cta_with_non_string_prop_type['props']['properties']['text']['type'] = [
      'string',
      [],
    ];
    yield 'non string type (Array)' => [$cta_with_non_string_prop_type];
  }

  /**
   * Tests that valid props are handled properly.
   *
   * @dataProvider dataProviderValidatePropsValid
   *
   * @throws \Drupal\Core\Render\Component\Exception\InvalidComponentException
   */
  public function testValidatePropsValid(array $context, string $component_id, array $definition): void {
    $component = new Component(
      ['app_root' => '/fake/path/root'],
      'sdc_test:' . $component_id,
      $definition
    );
    $component_validator = new ComponentValidator();
    $component_validator->setValidator();
    $this->assertTrue(
      $component_validator->validateProps($context, $component),
      'The valid component props threw an error.'
    );
  }

  /**
   * Data provider with valid component props.
   *
   * @return array<int|string, array<int,array<\Drupal\Core\Template\Attribute|\stdClass|string>|string>>
   *   The data.
   */
  public static function dataProviderValidatePropsValid(): array {
    return [
      [
        [
          'text' => 'Can Pica',
          'href' => 'https://www.drupal.org',
          'target' => '_blank',
          'attributes' => new Attribute(['key' => 'value']),
        ],
        'my-cta',
        static::loadComponentDefinitionFromFs('my-cta'),
      ],
      [[], 'my-banner', static::loadComponentDefinitionFromFs('my-banner')],
      [
        ['nonProp' => new \stdClass()],
        'my-banner',
        static::loadComponentDefinitionFromFs('my-banner'),
      ],
      'CT simple component with optional props (no prop)' => [
        [],
        'ct-simple-component',
        static::loadComponentDefinitionFromFs('ct-simple-component'),
      ],
      'CT simple component with optional props (string prop)' => [
        ['title' => '[TEST] title'],
        'ct-simple-component',
        static::loadComponentDefinitionFromFs('ct-simple-component'),
      ],
      'CT simple component with empty string optional props (empty string prop)' => [
        ['title' => ''],
        'ct-simple-component',
        static::loadComponentDefinitionFromFs('ct-simple-component'),
      ],
    ];
  }

  /**
   * Tests that invalid props are handled properly.
   *
   * @dataProvider dataProviderValidatePropsInvalid
   *
   * @throws \Drupal\Core\Render\Component\Exception\InvalidComponentException
   */
  public function testValidatePropsInvalid(array $context, string $component_id, array $definition, string $expected_exception_message): void {
    $component = new Component(
      ['app_root' => '/fake/path/root'],
      'sdc_test:' . $component_id,
      $definition
    );
    $this->expectException(InvalidComponentException::class);
    $this->expectExceptionMessage($expected_exception_message);
    $component_validator = new ComponentValidator();
    $component_validator->setValidator();
    $component_validator->validateProps($context, $component);
  }

  /**
   * Data provider with invalid component props.
   *
   * @return array<string, array<int,array<bool|\Drupal\Core\Template\Attribute|int|\stdClass|string|null>|string>>
   *   Returns the generator with the invalid properties.
   */
  public static function dataProviderValidatePropsInvalid(): array {
    return [
      'missing required prop' => [
        [
          'href' => 'https://www.drupal.org',
          'target' => '_blank',
          'attributes' => new Attribute(['key' => 'value']),
        ],
        'my-cta',
        static::loadComponentDefinitionFromFs('my-cta'),
        '[sdc_test:my-cta/text] The property text is required.',
      ],
      'attributes with invalid object class' => [
        [
          'text' => 'Can Pica',
          'href' => 'https://www.drupal.org',
          'target' => '_blank',
          'attributes' => new \stdClass(),
        ],
        'my-cta',
        static::loadComponentDefinitionFromFs('my-cta'),
        'Data provided to prop "attributes" for component "sdc_test:my-cta" is not a valid instance of "Drupal\Core\Template\Attribute"',
      ],
      'ctaTarget violates the allowed properties in the enum' => [
        ['ctaTarget' => 'foo'],
        'my-banner',
        static::loadComponentDefinitionFromFs('my-banner'),
        '[sdc_test:my-banner/ctaTarget] Does not have a value in the enumeration ["","_blank"]. The provided value is: "foo".',
      ],
      'CT simple component invalid integer prop type provided' => [
        ['title' => 1],
        'ct-simple-component',
        static::loadComponentDefinitionFromFs('ct-simple-component'),
        '[sdc_test:ct-simple-component/title] Integer value found, but a string or an object is required. The provided value is: "1".',
      ],
      'CT simple component invalid null prop type provided' => [
        ['title' => NULL],
        'ct-simple-component',
        static::loadComponentDefinitionFromFs('ct-simple-component'),
        '[sdc_test:ct-simple-component/title] NULL value found, but a string or an object is required. This may be because the property is empty instead of having data present. If possible fix the source data, use the |default() twig filter, or update the schema to allow multiple types..',
      ],
      'CT simple component with required prop missing required prop and optional prop' => [
        [],
        'ct-simple-component--required',
        static::loadComponentDefinitionFromFs('ct-simple-component--required'),
        '[sdc_test:ct-simple-component--required/title] The property title is required.',
      ],
      'CT simple component with required prop but with wrong type' => [
        ['title' => FALSE, 'message' => 'test'],
        'ct-simple-component--required',
        static::loadComponentDefinitionFromFs('ct-simple-component--required'),
        '[sdc_test:ct-simple-component--required/title] Boolean value found, but a string or an object is required. The provided value is: "".',
      ],
    ];
  }

  /**
   * Loads a component definition from the component name.
   *
   * @param string $component_name
   *   The component name.
   *
   * @return array<mixed>
   *   The component definition
   */
  protected static function loadComponentDefinitionFromFs(string $component_name): array {
    return array_merge(
      Yaml::parseFile(
        sprintf('%s/fixtures/components/%s/%s.component.yml', dirname(__DIR__, 2), $component_name, $component_name),
      ),
      [
        'machineName' => $component_name,
        'extension_type' => 'module',
        'id' => 'sdc_test:' . $component_name,
        'library' => ['css' => ['component' => ['foo.css' => []]]],
        'path' => '',
        'provider' => 'sdc_test',
        'template' => $component_name . '.twig',
        'group' => 'my-group',
        'description' => 'My description',
      ]
    );
  }

}
