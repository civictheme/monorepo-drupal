<?php

/**
 * Class ExtractCssColorsToCsvScriptUnitTest.
 *
 * Unit tests for extract_css_colors_to_csv.php.
 *
 * @group scripts
 */
class ExtractCssColorsToCsvScriptUnitTest extends ScriptUnitTestBase {

  /**
   * {@inheritdoc}
   */
  protected $script = 'docroot/themes/contrib/civictheme/scripts/extract_css_colors_to_csv.php';

  /**
   * @dataProvider dataProviderMain
   * @runInSeparateProcess
   */
  public function testMain($args, $expected_code, $expected_output) {
    $args = is_array($args) ? $args : [$args];
    $result = $this->runScript($args);
    $this->assertEquals($expected_code, $result['code']);
    $this->assertStringContainsString($expected_output, $result['output']);
  }

  public function dataProviderMain() {
    return [
      [
        '--help',
        0,
        'Extract CSS4 variables into CSV.',
      ],
      [
        '-help',
        0,
        'Extract CSS4 variables into CSV.',
      ],
      [
        '-h',
        0,
        'Extract CSS4 variables into CSV.',
      ],
      [
        '-?',
        0,
        'Extract CSS4 variables into CSV.',
      ],
      [
        [1, 2],
        0,
        'Extract CSS4 variables into CSV.',
      ],

      // Validation of path existence.
      [
        'some/non_existing/variables.css',
        1,
        'ERROR: CSS variables file some/non_existing/variables.css in not readable.',
      ],
    ];
  }

  /**
   * @dataProvider dataProviderCollectVariables
   * @runInSeparateProcess
   */
  public function testCollectVariables($content, $expected) {
    $this->assertEquals($expected, collect_variables($content));
  }

  public function dataProviderCollectVariables() {
    return [
      ['', []],
      // Valid - single.
      ['--var1:val1;', ['--var1' => 'val1']],
      ['--var1: val1;', ['--var1' => 'val1']],
      ['--var1:  val1;', ['--var1' => 'val1']],
      ['--var1:  val1 ;', ['--var1' => 'val1']],
      ['--var1 :  val1 ;', ['--var1' => 'val1']],
      ['--var1  :  val1 ;', ['--var1' => 'val1']],
      ['--var1  : val1 ;', ['--var1' => 'val1']],
      ['--var1  :val1 ;', ['--var1' => 'val1']],
      [' --var1  :val1 ;', ['--var1' => 'val1']],
      ['  --var1  :val1 ;', ['--var1' => 'val1']],
      ['  --var1  : val1 ;', ['--var1' => 'val1']],
      ['  --var1  :  val1 ;', ['--var1' => 'val1']],
      ['  --var1  :  val1  ;', ['--var1' => 'val1']],

      // Valid - multiple.
      ['--var1:val1;--var2:val2;', ['--var1' => 'val1', '--var2' => 'val2']],
      ['--var1:val1; --var2:val2;', ['--var1' => 'val1', '--var2' => 'val2']],
      ['--var1:val1; --var2:val2 ;', ['--var1' => 'val1', '--var2' => 'val2']],
      ['--var1: val1; --var2:val2 ;', ['--var1' => 'val1', '--var2' => 'val2']],
      ['--var1: val1; --var2: val2 ;', ['--var1' => 'val1', '--var2' => 'val2']],
      [' --var1: val1; --var2: val2 ;', ['--var1' => 'val1', '--var2' => 'val2']],
      [' --var1: val1;  --var2: val2 ;', ['--var1' => 'val1', '--var2' => 'val2']],
      [' --var1: val1 ;  --var2: val2 ;', ['--var1' => 'val1', '--var2' => 'val2']],
      [' --var1:  val1 ;  --var2: val2 ;', ['--var1' => 'val1', '--var2' => 'val2']],

      // Valid - multiple - mixed.
      ['html{--var1:val1;--var2:val2;}', ['--var1' => 'val1', '--var2' => 'val2']],
      ['html{ --var1:val1;--var2:val2;}', ['--var1' => 'val1', '--var2' => 'val2']],
      ['html{ --var1:val1; --var2:val2;}', ['--var1' => 'val1', '--var2' => 'val2']],
      ['html{ --var1:val1; --var2:val2; }', ['--var1' => 'val1', '--var2' => 'val2']],

      // Valid - multiple - mixed - multiline.
      ["html{\n--var1:val1;\n--var2:val2;\n}", ['--var1' => 'val1', '--var2' => 'val2']],
      ["html{\n--var1:\nval1;\n--var2:val2;\n}", ['--var1' => 'val1', '--var2' => 'val2']],
      ["html{\n--var1:\nval1;\n--var2:\nval2;\n}", ['--var1' => 'val1', '--var2' => 'val2']],
      ["html\n{\n--var1:\nval1;\n--var2:\nval2;\n}", ['--var1' => 'val1', '--var2' => 'val2']],

      // Valid - multiple - mixed - non-vars.
      ['html{--var1:val1;--var2:val2;var3:val3;}', ['--var1' => 'val1', '--var2' => 'val2']],
      ['html{--var1:val1;--var2:val2; var3: val3;}', ['--var1' => 'val1', '--var2' => 'val2']],
      ['html{--var1:val1;--var2:val2; var3: val3 ;}', ['--var1' => 'val1', '--var2' => 'val2']],

      // Invalid.
      ['--var1:val1', []],
      ['--var1 val1', []],
      ['--var1;--var2:val2;', ['--var2' => 'val2']],
      ['--var1; --var2:val2;', ['--var2' => 'val2']],
      ['--var1; --var2:val2 ;', ['--var2' => 'val2']],
      ['--var1 val1;--var2:val2;', ['--var2' => 'val2']],
      ["--var1 val1;\n--var2:val2;", ['--var2' => 'val2']],
      ["--var1:val1;\n--var2 val2;", ['--var1' => 'val1']],
      ["--var1:val1; --var2 val2; --var3: val3;", ['--var1' => 'val1', '--var3' => 'val3']],
    ];
  }

  /**
   * @dataProvider dataProviderParseVariableName
   * @runInSeparateProcess
   */
  public function testParseVariableName($name, $prefix, $expected, $expectExceptionMessage = FALSE) {
    if ($expectExceptionMessage) {
      $this->expectException(\Exception::class);
      $this->expectExceptionMessage($expectExceptionMessage);
    }

    $this->assertEquals($expected, parse_variable_name($name, $prefix));
  }

  public function dataProviderParseVariableName() {
    return [
      ['', '', '', 'Empty name provided.'],

      // Valid short variable names.
      [
        'component1-light-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => '',
          'state' => '',
          'rule' => 'color',
        ],
      ],
      [
        'component1-dark-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'dark',
          'subcomponent' => '',
          'state' => '',
          'rule' => 'color',
        ],
      ],

      // Valid short variable names.
      [
        'component-long-name1-light-color',
        '',
        [
          'component' => 'component long name1',
          'theme' => 'light',
          'subcomponent' => '',
          'state' => '',
          'rule' => 'color',
        ],
      ],
      [
        'component1-light-background-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => '',
          'state' => '',
          'rule' => 'background-color',
        ],
      ],
      [
        'component1-light-border-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => '',
          'state' => '',
          'rule' => 'border-color',
        ],
      ],
      [
        'component1-light-border-left-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => '',
          'state' => '',
          'rule' => 'border-left-color',
        ],
      ],
      [
        'component1-light-border-top-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => '',
          'state' => '',
          'rule' => 'border-top-color',
        ],
      ],
      [
        'component1-light-border-bottom-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => '',
          'state' => '',
          'rule' => 'border-bottom-color',
        ],
      ],
      [
        'component1-light-border-right-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => '',
          'state' => '',
          'rule' => 'border-right-color',
        ],
      ],

      // Valid long variable names.
      [
        'component1-light-sub1-background-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => 'sub1',
          'state' => '',
          'rule' => 'background-color',
        ],
      ],
      [
        'component1-light-sub1-sub11-background-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => 'sub1 sub11',
          'state' => '',
          'rule' => 'background-color',
        ],
      ],
      [
        'component1-light-sub1-sub11-sub111-background-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => 'sub1 sub11 sub111',
          'state' => '',
          'rule' => 'background-color',
        ],
      ],
      [
        'component1-light-sub1-sub11-sub111-active-background-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => 'sub1 sub11 sub111',
          'state' => 'active',
          'rule' => 'background-color',
        ],
      ],
      [
        'component1-light-sub1-sub11-sub111-hover-background-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => 'sub1 sub11 sub111',
          'state' => 'hover',
          'rule' => 'background-color',
        ],
      ],
      [
        'component1-light-sub1-sub11-sub111-disabled-background-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => 'sub1 sub11 sub111',
          'state' => 'disabled',
          'rule' => 'background-color',
        ],
      ],
      [
        'component1-light-sub1-sub11-sub111-visited-background-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => 'sub1 sub11 sub111',
          'state' => 'visited',
          'rule' => 'background-color',
        ],
      ],

      // Short states.
      [
        'component1-light-visited-background-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => '',
          'state' => 'visited',
          'rule' => 'background-color',
        ],
      ],

      // Invalid - missing rule.
      [
        'component1-light-visited',
        '',
        '',
        'Incorrectly named variable component1-light-visited: rule is missing.',
      ],
      // Invalid - missing component name.
      [
        'light-visited-background-color',
        '',
        '',
        'Incorrectly named variable light-visited-background-color: component name is missing.',
      ],
      // Invalid - missing theme name.
      [
        'component1-background-color',
        '',
        '',
        'Incorrectly named variable component1-background-color: theme is missing.',
      ],
      // Invalid - incorrect order.
      [
        'component1-background-color-light',
        '',
        '',
        'Incorrectly named variable component1-background-color-light: rule is missing.',
      ],
      [
        'component1-light-background-color-light',
        '',
        '',
        'Incorrectly named variable component1-light-background-color-light: rule is missing.',
      ],
      [
        'component1-light-background-color-active',
        '',
        '',
        'Incorrectly named variable component1-light-background-color-active: rule is missing.',
      ],

      // Invalid - incorrect theme or rule.
      [
        'component1-light2-sub1-background-color',
        '',
        '',
        'Incorrectly named variable component1-light2-sub1-background-color: theme is missing.',
      ],
      [
        'component1-light-sub1-background-color1',
        '',
        '',
        'Incorrectly named variable component1-light-sub1-background-color1: rule is missing.',
      ],

      // Special case - invalid state is parsed as a sub-component name.
      [
        'component1-light-sub1-active1-background-color',
        '',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => 'sub1 active1',
          'state' => '',
          'rule' => 'background-color',
        ],
        '',
      ],

      // Prefix.
      [
        'someprefix-component1-light-sub1-active-background-color',
        '',
        [
          'component' => 'someprefix component1',
          'theme' => 'light',
          'subcomponent' => 'sub1',
          'state' => 'active',
          'rule' => 'background-color',
        ],
        '',
      ],

      [
        'someprefix-component1-light-sub1-active-background-color',
        'someprefix',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => 'sub1',
          'state' => 'active',
          'rule' => 'background-color',
        ],
        '',
      ],

      [
        'someprefix-component1-light-sub1-active-background-color',
        'someprefix1',
        [
          'component' => 'someprefix component1',
          'theme' => 'light',
          'subcomponent' => 'sub1',
          'state' => 'active',
          'rule' => 'background-color',
        ],
        '',
      ],

      // Valid - With '--'.
      [
        '--someprefix-component1-light-sub1-sub11-active-background-color',
        'someprefix',
        [
          'component' => 'component1',
          'theme' => 'light',
          'subcomponent' => 'sub1 sub11',
          'state' => 'active',
          'rule' => 'background-color',
        ],
        '',
      ],
      [
        '--someprefix-component-long-name1-light-sub1-sub11-active-background-color',
        'someprefix',
        [
          'component' => 'component long name1',
          'theme' => 'light',
          'subcomponent' => 'sub1 sub11',
          'state' => 'active',
          'rule' => 'background-color',
        ],
        '',
      ],
    ];
  }

}
