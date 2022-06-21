<?php

/**
 * @file
 * Variations for CivicTheme Page nodes.
 */

use Drupal\cs_generated_content\CsGeneratedContentHelper;

/**
 * Page, Components - Listing.
 */
function _cs_generated_content_create_node_civictheme_page__variations__component_listing() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  return [
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Promo Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Promo Card, Topic',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'topics' => [
            $helper::randomRealTerm('civictheme_topics')->id(),
          ]
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Promo Card, Topics',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'topics' => [
            $helper::randomRealTerm('civictheme_topics')->id(),
            $helper::randomRealTerm('civictheme_topics')->id(),
          ]
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Promo Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Navigation Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Navigation Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Promo Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Promo Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Navigation Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Navigation Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Promo Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Promo Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Navigation Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Navigation Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Promo Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Promo Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Navigation Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Navigation Card',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, 3 on page',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
        ],
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
        ],
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Promo Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Promo Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Navigation Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Navigation Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Promo Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Promo Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Navigation Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Navigation Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Promo Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Promo Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Navigation Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Navigation Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Promo Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Promo Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Navigation Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Navigation Card, Dark',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Promo Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Promo Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Navigation Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Navigation Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Promo Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Promo Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Navigation Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Navigation Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Promo Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Promo Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Navigation Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Navigation Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Promo Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Promo Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Navigation Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Navigation Card, Light, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Promo Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Promo Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Navigation Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Navigation Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Promo Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Promo Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Navigation Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Navigation Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Promo Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Promo Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Navigation Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Navigation Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Promo Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Promo Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Navigation Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Navigation Card, Dark, BG',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Promo Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Promo Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Navigation Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Navigation Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Promo Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Promo Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Navigation Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Navigation Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Promo Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Promo Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Navigation Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Navigation Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Promo Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Promo Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Navigation Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Navigation Card, Light, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Promo Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Promo Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Navigation Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Navigation Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Promo Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Promo Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Navigation Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Navigation Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Promo Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Promo Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Navigation Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Navigation Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Promo Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Promo Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Navigation Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Navigation Card, Dark, No BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeDark(),
          'background' => FALSE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Promo Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Promo Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Navigation Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Navigation Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Promo Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Promo Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Navigation Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Navigation Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Promo Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Promo Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Navigation Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Navigation Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Promo Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Promo Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Navigation Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Navigation Card, Light, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Promo Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Promo Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Navigation Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Navigation Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Promo Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Promo Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Navigation Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Navigation Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Promo Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Promo Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Navigation Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Navigation Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Promo Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Promo Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Navigation Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Navigation Card, Dark, BG, Both spaces',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Promo Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Promo Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Navigation Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Navigation Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Promo Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Promo Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Navigation Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Navigation Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Promo Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Promo Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Navigation Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Navigation Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Promo Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Promo Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Navigation Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Navigation Card, Dark, BG, Both spaces, Multi select',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'listing_multi_select' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Promo Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Promo Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Navigation Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Navigation Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Promo Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Promo Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Navigation Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Navigation Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Promo Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Promo Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Navigation Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Navigation Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Promo Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Promo Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Navigation Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Navigation Card, Dark, BG, Both spaces, Show filters',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'show_filters' => TRUE,
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Promo Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Promo Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Light card, Navigation Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Light card, Navigation Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Promo Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Promo Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Light card, Navigation Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Light card, Navigation Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeLight(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Promo Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Promo Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Unlimited, Dark card, Navigation Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Page content, Limited - 2, Dark card, Navigation Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemePageContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Promo Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Promo Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemePromoCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Unlimited, Dark card, Navigation Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeUnlimitedType(),
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
    [
      'title' => 'Demo Page, Listing component, Event content, Limited - 2, Dark card, Navigation Card, Dark, BG, Both spaces, Title, Link, Read more',
      'components' => [
        [
          'type' => 'listing',
          'content_type' => $helper::civicthemeEventContentType(),
          'view_as' => $helper::civicthemeNavigationCardType(),
          'limit_type' => $helper::civicthemeLimitedType(),
          'listing_limit' => 2,
          'card_theme' => $helper::civicthemeThemeDark(),
          'theme' => $helper::civicthemeThemeLight(),
          'background' => TRUE,
          'space' => $helper::civicthemeSpaceTypeBoth(),
          'title' => $helper::staticSentence(3),
          'read_more' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
          'link' => [
            'uri' => $helper::randomUrl(),
            'title' => $helper::staticString(30),
          ],
        ],
      ],
    ],
  ];
}
