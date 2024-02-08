<?php

/**
 * @file
 * CivicTheme Drupal context for Behat testing.
 */

declare(strict_types=1);

use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use DrevOps\BehatSteps\ContentTrait;
use DrevOps\BehatSteps\DateTrait;
use DrevOps\BehatSteps\ElementTrait;
use DrevOps\BehatSteps\FieldTrait;
use DrevOps\BehatSteps\FileTrait;
use DrevOps\BehatSteps\JsTrait;
use DrevOps\BehatSteps\LinkTrait;
use DrevOps\BehatSteps\MediaTrait;
use DrevOps\BehatSteps\MenuTrait;
use DrevOps\BehatSteps\OverrideTrait;
use DrevOps\BehatSteps\ParagraphsTrait;
use DrevOps\BehatSteps\PathTrait;
use DrevOps\BehatSteps\SelectTrait;
use DrevOps\BehatSteps\TaxonomyTrait;
use DrevOps\BehatSteps\TestmodeTrait;
use DrevOps\BehatSteps\VisibilityTrait;
use DrevOps\BehatSteps\WaitTrait;
use DrevOps\BehatSteps\WatchdogTrait;
use DrevOps\BehatSteps\WysiwygTrait;
use Drupal\Core\Extension\Exception\UnknownExtensionException;
use Drupal\Core\Url;
use Drupal\DrupalExtension\Context\DrupalContext;
use Drupal\node\Entity\Node;
use Drupal\search_api\Plugin\search_api\datasource\ContentEntity;

/**
 * Defines application features from the specific context.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class FeatureContext extends DrupalContext {

  use ContentTrait;
  use DateTrait;
  use ElementTrait;
  use FieldTrait;
  use FileTrait;
  use JsTrait;
  use LinkTrait;
  use MediaTrait;
  use MenuTrait;
  use OverrideTrait;
  use PathTrait;
  use ParagraphsTrait;
  use SelectTrait;
  use TaxonomyTrait;
  use TestmodeTrait;
  use WatchdogTrait;
  use WaitTrait;
  use WysiwygTrait;
  use VisibilityTrait;

  /**
   * Assert that content is present in an iframe.
   *
   * @Then I see content in iframe with id :id
   */
  public function iSeeContentInIframe(string $id): void {
    $driver = $this->getSession()->getDriver();
    if (!$driver instanceof Selenium2Driver) {
      throw new \RuntimeException('Unsupported driver for this step');
    }

    $index = NULL;

    $iframe_elements = $driver->find('//iframe');
    if (!empty($iframe_elements)) {
      foreach ($iframe_elements as $k => $all_page_iframe_element) {
        if ($all_page_iframe_element->getAttribute('id') == $id) {
          $index = $k;
          break;
        }
      }
    }

    if (is_null($index)) {
      throw new ElementNotFoundException($driver, 'iFrame', $id);
    }

    // @phpstan-ignore-next-line
    $driver->switchToIFrame((int) $index);

    if (!$driver->find('//body')) {
      throw new \Exception(sprintf('The contents of the iFrame with id "%s" was not loaded', $id));
    }

    // Reset frame to the default window.
    $driver->switchToIFrame();
  }

  /**
   * Selects a filter chip.
   *
   * @When I select the filter chip :label
   */
  public function iSelectFilterChip(string $label): void {
    $element = $this->getSession()->getPage();
    $filter_chip = $element->find('named', [
      'radio',
      $label,
    ]);
    if ($filter_chip === NULL) {
      throw new \Exception(sprintf('The filter chip with "%s" was not found on the page %s.', $label, $this->getSession()->getCurrentUrl()));
    }

    $this->clickFilterChip($label, $filter_chip, $element);
  }

  /**
   * Checks a multi-value filter chip.
   *
   * @Given I check the filter chip :label
   */
  public function assertCheckFilterChip(string $label): void {
    $element = $this->getSession()->getPage();
    $filter_chip = $element->find('named', [
      'checkbox',
      $label,
    ]);

    if ($filter_chip === NULL) {
      throw new \Exception(sprintf('The filter chip with "%s" was not found on the page %s.', $label, $this->getSession()->getCurrentUrl()));
    }

    if ($filter_chip->isChecked()) {
      throw new \Exception(sprintf('Cannot check filter chip with "%s" because it already checked on the page %s.', $label, $this->getSession()->getCurrentUrl()));
    }

    $this->clickFilterChip($label, $filter_chip, $element);
  }

  /**
   * Unchecks a multi-value filter chip.
   *
   * @Given I uncheck the filter chip :checkbox
   */
  public function assertUncheckFilterChip(string $label): void {
    $page = $this->getSession()->getPage();
    $filter_chip = $page->find('named', [
      'checkbox',
      $label,
    ]);

    if ($filter_chip === NULL) {
      throw new \Exception(sprintf('The filter chip with "%s" was not found on the page %s.', $label, $this->getSession()->getCurrentUrl()));
    }

    if (!$filter_chip->isChecked()) {
      throw new \Exception(sprintf('Cannot uncheck filter chip with "%s" because it not checked on the page %s.', $label, $this->getSession()->getCurrentUrl()));
    }

    $this->clickFilterChip($label, $filter_chip, $page);
  }

  /**
   * Helper to get the filter chip label element which is then clicked.
   */
  protected function clickFilterChip(string $label, NodeElement $filter_chip, DocumentElement $page): void {
    $filter_chip_id = $filter_chip->getAttribute('id');
    $label_element = $page->find('css', sprintf("label[for='%s']", $filter_chip_id));
    $label_on_page = $label_element->getText();
    if ($label != $label_on_page) {
      throw new \Exception(sprintf("Filter chip with id '%s' has label '%s' instead of '%s' on the page %s", $filter_chip_id, $label_on_page, $label, $this->getSession()->getCurrentUrl()));
    }

    $label_element->click();
  }

  /**
   * Creates paragraphs of the given type with fields for existing entity.
   *
   * Paragraph fields are specified in the same way as for nodeCreate():
   * | field_paragraph_title           | My paragraph title   |
   * | field_paragraph_longtext:value  | My paragraph message |
   * | field_paragraph_longtext:format | full_html            |
   * | ...                             | ...                  |
   *
   * @When :field_name in :bundle :entity_type parent :parent_entity_field in :parent_entity_bundle :parent_entity_type with :parent_entity_field_name of :parent_entity_field_identifer delta :delta has :paragraph_type paragraph:
   *
   * @SuppressWarnings(PHPMD.ExcessiveParameterList)
   */
  public function paragraphsAddToParentEntityWithFields(string $field_name, string $bundle, string $entity_type, string $parent_entity_field, string $parent_entity_bundle, string $parent_entity_type, string $parent_entity_field_name, string $parent_entity_field_identifer, int $delta, string $paragraph_type, TableNode $fields): void {
    // Get paragraph field name for this entity type.
    $paragraph_node_field_name = $this->paragraphsCheckEntityFieldName($entity_type, $bundle, $field_name);

    // Find previously created entity by entity_type, bundle and identifying
    // field value.
    $node = $this->paragraphsFindEntity([
      'field_value' => $parent_entity_field_identifer,
      'field_name' => $parent_entity_field_name,
      'bundle' => $parent_entity_bundle,
      'entity_type' => $parent_entity_type,
    ]);

    $referenceItem = $node->get($parent_entity_field)->get($delta);
    if (!$referenceItem) {
      throw new \Exception(sprintf('Unable to find entity that matches delta: "%s"', print_r($delta, TRUE)));
    }

    $entity = $referenceItem->get('entity')->getTarget()->getValue();

    // Get fields from scenario, parse them and expand values according to
    // field tables.
    $stub = (object) $fields->getRowsHash();
    $stub->type = $paragraph_type;
    $this->parseEntityFields('paragraph', $stub);
    $this->paragraphsExpandEntityFields('paragraph', $stub);

    // Attach paragraph from stub to node.
    $this->paragraphsAttachFromStubToEntity($entity, $paragraph_node_field_name, $paragraph_type, $stub);
  }

  /**
   * Scroll to an element with ID.
   *
   * @Then /^I scroll to an? element with id "([^"]*)"$/
   */
  public function iScrollToElementWithId(string $id): void {
    $this->getSession()->executeScript("
      var element = document.getElementById('" . $id . "');
      element.scrollIntoView( true );
    ");
  }

  /**
   * Assert link with a href does not exist.
   *
   * Note that simplified wildcard is supported in "href".
   *
   * @code
   * Then I should not see the link "About us" with "/about-us"
   * Then I should not see the link "About us" with "/about-us" in ".main-nav"
   * Then I should not see the link "About us" with "/about*" in ".main-nav"
   * @endcode
   *
   * @Then I should not see the link :text with :href
   * @Then I should not see the link :text with :href in :locator
   *
   * @todo Remove with next behat-steps release.
   */
  public function linkAssertTextHrefNotExists(string $text, string $href, string $locator = NULL): void {
    /** @var \Behat\Mink\Element\DocumentElement $page */
    $page = $this->getSession()->getPage();

    $element = $page;
    if ($locator) {
      $element = $page->find('css', $locator);
      if ($element === NULL) {
        return;
      }
    }

    $link = $element->findLink($text);
    if ($link === NULL) {
      return;
    }

    if (!$link->hasAttribute('href')) {
      return;
    }

    $pattern = '/' . preg_quote($href, '/') . '/';
    // Support for simplified wildcard using '*'.
    $pattern = str_contains($href, '*') ? str_replace('\*', '.*', $pattern) : $pattern;
    if (preg_match($pattern, (string) $link->getAttribute('href'))) {
      throw new \Exception(sprintf('The link href "%s" matches the specified href "%s" but should not', $link->getAttribute('href'), $href));
    }
  }

  /**
   * Fills in form color field with specified id|name|label|value.
   *
   * Example: When I fill color in "#colorpickerHolder" with: "#ffffff"
   *
   * @When /^(?:|I )fill color in "(?P<field>(?:[^"]|\\")*)" with "(?P<value>(?:[^"]|\\")*)"$/
   * @When /^(?:|I )fill color in "(?P<field>(?:[^"]|\\")*)" with:$/
   * @When /^(?:|I )fill color in "(?P<value>(?:[^"]|\\")*)" for "(?P<field>(?:[^"]|\\")*)"$/
   */
  public function fillColorField(string $field, string $value): mixed {
    $js = sprintf(
      'jQuery("%s").val("%s").change();',
      $field,
      $value
    );

    return $this->getSession()->evaluateScript($js);
  }

  /**
   * Asserts that a color field has a value.
   *
   * @Then /^color field "(?P<field>(?:[^"]|\\")*)" value is "(?P<value>(?:[^"]|\\")*)"$/
   */
  public function assertColorFieldHasValue(string $field, string $value): void {
    $js = sprintf('jQuery("%s").val();', $field);

    $actual = $this->getSession()->evaluateScript($js);

    if ($actual != $value) {
      throw new \Exception(sprintf('Color field "%s" expected a value "%s" but has a value "%s".', $field, $value, $actual));
    }
  }

  /**
   * Install a theme with provided name.
   *
   * @When I install :name theme
   */
  public function installTheme(string $name): void {
    \Drupal::service('theme_installer')->install([$name]);
  }

  /**
   * Uninstall a theme with provided name.
   *
   * @When I uninstall :name theme
   */
  public function uninstallTheme(string $name): void {
    try {
      \Drupal::service('theme_installer')->uninstall([$name]);
    }
    catch (UnknownExtensionException) {
      print sprintf('The "%s" theme is not installed.', $name);
    }
  }

  /**
   * Set theme as default.
   *
   * @When I set :name theme as default
   */
  public function setThemeAsDefault(string $name): void {
    \Drupal::service('config.factory')->getEditable('system.theme')
      ->set('default', $name)
      ->save();
  }

  /**
   * Visit a settings page of the theme.
   *
   * @When I visit :name theme settings page
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function themeVisitSettings(string $name = NULL): void {
    if (!$name || $name == 'current') {
      $name = \Drupal::theme()->getActiveTheme()->getName();
    }

    if (!\Drupal::service('theme_handler')->themeExists($name)) {
      throw new \RuntimeException(sprintf('Theme %s does not exist.', $name));
    }

    $url = Url::fromRoute('system.theme_settings_theme', ['theme' => $name]);

    $this->visitPath($url->toString());
  }

  /**
   * Remove menu links by title.
   *
   * Fixed upstream method incorrectly throwing error on non-existing items.
   *
   * Provide menu link titles in the following format:
   * | Test Menu    |
   * | ...          |
   *
   * @Given no :menu_name menu_links:
   */
  public function menuLinksDelete(string $menu_name, TableNode $table): void {
    foreach ($table->getColumn(0) as $title) {
      try {
        $menu_link = $this->loadMenuLinkByTitle($title, $menu_name);
        if ($menu_link) {
          $menu_link->delete();
        }
      }
      catch (\Exception) {
        continue;
      }
    }
  }

  /**
   * Remove block defined by machine name.
   *
   * @code
   * Given no blocks:
   * | user_login |
   * @endcode
   *
   * @Given no blocks:
   */
  public function blockDelete(TableNode $table): void {
    foreach ($table->getColumn(0) as $id) {
      try {
        $block = \Drupal::entityTypeManager()
          ->getStorage('block')
          ->load($id);

        if (empty($block)) {
          throw new \Exception(sprintf('Unable to find block "%s"', $id));
        }

        $block->delete();
      }
      catch (\Exception) {
        continue;
      }
    }
  }

  /**
   * Remove block_content defined by info.
   *
   * @code
   * Given no content blocks:
   * | Component block |
   * @endcode
   *
   * @Given no content blocks:
   */
  public function contentBlockDelete(TableNode $table): void {
    foreach ($table->getColumn(0) as $info) {
      try {
        $entities = \Drupal::entityTypeManager()
          ->getStorage('block_content')
          ->loadByProperties(['info' => $info]);

        if (empty($entities)) {
          throw new \Exception(sprintf('Unable to find block_content with info "%s"', $info));
        }

        $entity = reset($entities);
        $entity->delete();
      }
      catch (\Exception) {
        continue;
      }
    }
  }

  /**
   * Index a node with all Search API indices.
   *
   * @When I index :type :title for search
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function searchApiIndexContent(string $type, string $title): void {
    $nids = $this->contentNodeLoadMultiple($type, [
      'title' => $title,
    ]);

    if (empty($nids)) {
      throw new \RuntimeException(sprintf('Unable to find %s page "%s"', $type, $title));
    }

    ksort($nids);
    $nid = end($nids);
    $node = Node::load($nid);

    /** @var \Drupal\node\NodeInterface $node */
    $translations = array_keys($node->getTranslationLanguages());
    $get_ids = static function (string $langcode) use ($nid) : string {
      return $nid . ':' . $langcode;
    };
    $index_ids = array_map($get_ids, $translations);
    $item_id = 'entity:node/' . $nid . ':' . reset($translations);

    $indexes = ContentEntity::getIndexesForEntity($node);
    foreach ($indexes as $index) {
      $index->trackItemsInserted('entity:node', $index_ids);
      $index->indexSpecificItems([$item_id => $index->loadItem($item_id)]);
    }
  }

  /**
   * Set value for WYSIWYG field.
   *
   * If used with Selenium driver, it will try to find associated WYSIWYG and
   * fill it in. If used with webdriver - it will fill in the field as normal.
   *
   * @When /^(?:|I )fill in WYSIWYG "(?P<field>(?:[^"]|\\")*)" with "(?P<value>(?:[^"]|\\")*)"$/
   */
  public function wysiwygFillField(string $field, string $value): void {
    $field = $this->wysiwygFixStepArgument($field);
    $value = $this->wysiwygFixStepArgument($value);

    // Convert ot hyphenated machine name.
    $field = str_replace('_', '-', (string) $field);

    $this->getSession()
      ->executeScript(
        "
        const domEditableElement = document.querySelector(\"div.form-item--{$field}-0-value .ck-editor__editable\");
        if (domEditableElement.ckeditorInstance) {
          const editorInstance = domEditableElement.ckeditorInstance;
          if (editorInstance) {
            editorInstance.setData(\"{$value}\");
          } else {
            throw new Exception('Could not get the editor instance');
          }
        } else {
          throw new Exception('Could not find the element');
        }
        ");
  }

}
