<?php

/**
 * @file
 * Link related functions.
 */

declare(strict_types=1);

use Drupal\Component\Utility\Html;
use Drupal\Core\Language\LanguageInterface;
use Drupal\filter\Entity\FilterFormat;
use Drupal\filter\FilterPluginCollection;

/**
 * Process HTML content to replace URLs with links.
 *
 * @param string $html
 *   HTML to process.
 * @param string|null $format
 *   Format to process.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function _civictheme_process_html_content_urls_to_links(string $html, ?string $format): string {
  // Check to see if a valid filter_format has been provided and whether
  // link processing is enabled for that format.
  if (is_string($format)) {
    $format = \Drupal::entityTypeManager()
      ->getStorage('filter_format')
      ->load($format);
  }
  if ($format instanceof FilterFormat) {
    /** @var \Drupal\filter\Plugin\FilterBase $filter */
    $filter = $format->filters('filter_url');
    if ($filter->status === FALSE) {
      return $html;
    }
    return $filter->process($html, LanguageInterface::LANGCODE_NOT_SPECIFIED)->getProcessedText();
  }
  // Fallback to a default instance.
  $filter_manager = \Drupal::service('plugin.manager.filter');
  $filter_collection = new FilterPluginCollection($filter_manager, []);
  $filter = $filter_collection->get('filter_url');

  return $filter->process($html, LanguageInterface::LANGCODE_NOT_SPECIFIED)->getProcessedText();
}

/**
 * Process HTML links within an HTML content string.
 *
 * @param string $html
 *   The string to be processed.
 * @param string $base_url
 *   The hostname to process.
 * @param bool $new_window
 *   Flag to open all links in the new window.
 * @param bool $external_new_window
 *   Flag to open external links in the new window.
 * @param array $override_domains
 *   Array of override domains.
 *
 * @return string
 *   The processed HTML string.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
function _civictheme_process_html_content_links(string $html, string $base_url, bool $new_window = FALSE, bool $external_new_window = FALSE, array $override_domains = []): string {
  if (empty($html)) {
    return $html;
  }

  try {
    $dom = Html::load($html);
  }
  catch (\Exception) {
    return $html;
  }

  $anchors = $dom->getElementsByTagName('a');
  if ($anchors->count() === 0) {
    return $html;
  }

  foreach ($anchors as $anchor) {
    // Apply only to anchors with links.
    if (!$anchor->hasAttribute('href')) {
      continue;
    }

    $url = $anchor->getAttribute('href');
    $is_external = civictheme_url_is_external($url, $base_url, $override_domains);

    if ($new_window || ($is_external && $external_new_window)) {
      if (!$anchor->hasAttribute('target')) {
        $anchor->setAttribute('target', '_blank');
      }

      if (!$anchor->hasAttribute('aria-label')) {
        $anchor->setAttribute('aria-label', (string) t('Opens in a new tab'));
      }

      _civictheme_link_add_external_decorations($dom, $anchor);
    }
  }

  $html = Html::serialize($dom);

  // Drupal versions prior to 10.2 used libxml2 for HTML parsing and
  // serialization in Html::serialize(), which does not handle processing of
  // SVGs correctly, resulting in inconsistent HTML5 output.
  // In order to mitigate this, SVGs are added as a string to the already
  // serialized HTML output.
  // @see https://www.drupal.org/node/3225468
  $svg_html = '<svg class="ct-icon ct-basic-content__external-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M17.9199 6.62C17.8185 6.37565 17.6243 6.18147 17.3799 6.08C17.2597 6.02876 17.1306 6.00158 16.9999 6H6.99994C6.73472 6 6.48037 6.10536 6.29283 6.29289C6.1053 6.48043 5.99994 6.73478 5.99994 7C5.99994 7.26522 6.1053 7.51957 6.29283 7.70711C6.48037 7.89464 6.73472 8 6.99994 8H14.5899L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.41V17C15.9999 17.2652 16.1053 17.5196 16.2928 17.7071C16.4804 17.8946 16.7347 18 16.9999 18C17.2652 18 17.5195 17.8946 17.707 17.7071C17.8946 17.5196 17.9999 17.2652 17.9999 17V7C17.9984 6.86932 17.9712 6.74022 17.9199 6.62Z" /></svg>';

  return str_replace('SVG_ICON_PLACEHOLDER', $svg_html, $html);
}

/**
 * Group the last word of a link with the external link icon.
 *
 * The word, the screen reader announcement and the icon share a non-wrapping
 * span so that the icon is never left on a line of its own. Markup around the
 * word, such as a `<strong>`, is preserved.
 *
 * @param \DOMDocument $dom
 *   The document the anchor belongs to.
 * @param \DOMElement $anchor
 *   The anchor to process.
 */
function _civictheme_link_add_external_decorations(\DOMDocument $dom, \DOMElement $anchor): void {
  $text_node = _civictheme_link_get_last_text_node($anchor);

  // Links without a text label, such as those wrapping an image, have no word
  // for the icon to be grouped with, so they are left as they are.
  if (!$text_node instanceof \DOMText || !$text_node->parentNode instanceof \DOMNode) {
    return;
  }

  $parts = _civictheme_link_split_last_word((string) $text_node->nodeValue);
  if ($parts === NULL) {
    return;
  }

  [, $preceding_text, $last_word, $trailing_whitespace] = $parts;

  $wrapper = $dom->createElement('span');
  $wrapper->setAttribute('class', 'ct-text-no-wrap');
  $wrapper->appendChild($dom->createTextNode($last_word));

  $span = $dom->createElement('span');
  $span->setAttribute('class', 'ct-visually-hidden');
  // The translated string is added as a text node so that any characters it
  // contains are escaped rather than parsed as markup.
  $span->appendChild($dom->createTextNode((string) t('(Opens in a new tab/window)')));
  $wrapper->appendChild($span);

  // Placeholder for external icon.
  $wrapper->appendChild($dom->createTextNode('SVG_ICON_PLACEHOLDER'));

  // The last word moves into the span, so only the text preceding it is left
  // in place, followed by the span and any whitespace that trailed the word.
  $text_node->nodeValue = $preceding_text;

  $fragment = $dom->createDocumentFragment();
  $fragment->appendChild($wrapper);

  if ($trailing_whitespace !== '') {
    $fragment->appendChild($dom->createTextNode($trailing_whitespace));
  }

  $parent = $text_node->parentNode;

  if ($text_node->nextSibling instanceof \DOMNode) {
    $parent->insertBefore($fragment, $text_node->nextSibling);
  }
  else {
    $parent->appendChild($fragment);
  }
}

/**
 * Find the last text node of an element that contains a word.
 *
 * @param \DOMNode $node
 *   The node to search within.
 *
 * @return \DOMText|null
 *   The last text node holding a word, or NULL if there is none.
 */
function _civictheme_link_get_last_text_node(\DOMNode $node): ?\DOMText {
  for ($child = $node->lastChild; $child instanceof \DOMNode; $child = $child->previousSibling) {
    if ($child instanceof \DOMText) {
      if (_civictheme_link_split_last_word((string) $child->nodeValue) !== NULL) {
        return $child;
      }

      continue;
    }

    // Comments and processing instructions hold no rendered text, so only
    // elements are descended into.
    if ($child instanceof \DOMElement) {
      $descendant = _civictheme_link_get_last_text_node($child);

      if ($descendant instanceof \DOMText) {
        return $descendant;
      }
    }
  }

  return NULL;
}

/**
 * Split the last word off a string of text.
 *
 * @param string $text
 *   The text to split.
 *
 * @return array|null
 *   Match array of the full text, the text before the last word, the last word
 *   and any whitespace after it, or NULL if the text holds no word.
 */
function _civictheme_link_split_last_word(string $text): ?array {
  // PHP's trim() strips ASCII whitespace only, which leaves behind the
  // non-breaking space that editors place alongside embedded media. The
  // character class covers the Unicode separators and the zero width
  // characters as well, so that a link wrapping nothing but an image is not
  // mistaken for a labelled one.
  $whitespace = '\s\x{00A0}\x{200B}\x{FEFF}';
  $pattern = sprintf('/^(.*?)([^%s]+)([%s]*)$/su', $whitespace, $whitespace);

  return preg_match($pattern, $text, $matches) === 1 ? $matches : NULL;
}
