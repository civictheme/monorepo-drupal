Feature: Test new Block Trait

@api
Scenario: Testing the new block trait

  # Configure a basic block
  When I create a block of type "Help" with:
    | label         | [TEST] Block Label      |
    | display_label | 1                |
    | region       | header_top_1        |
    | status       | 1              |

  And I am logged in as a "administrator"
  And I visit "/admin/structure/block"
  And save screenshot
  And I should see "[TEST] Block Label"
  And block with label "[TEST] Block Label" should exist in the region "header_top_1"
  And block with label "[TEST] Block Label" should not exist in the region "header_top_2"

  When I configure the block with the label "[TEST] Block Label" with:
    | label | [TEST] Updated Block Label |
    | region | header_top_2              |


  And I visit "/admin/structure/block"
  And save screenshot
  And I should see "[TEST] Updated Block Label"

  # Configure visibility conditions
  When I configure the visibility condition "request_path" for the block with label "[TEST] Updated Block Label"
    | pages  | /node/*,/user/* |
    | negate | false           |

  When I configure the visibility condition "entity_bundle:node" for the block with label "[TEST] Updated Block Label"
    | bundles | article,page |
    | negate  | true         |

  When I configure the visibility condition "user_role" for the block with label "[TEST] Updated Block Label"
    | roles  | authenticated,administrator |
    | negate | false                      |

  Then the block with label "[TEST] Updated Block Label" should have the visibility condition "request_path"
  Then the block with label "[TEST] Updated Block Label" should have the visibility condition "entity_bundle:node"
  Then the block with label "[TEST] Updated Block Label" should have the visibility condition "user_role"
  And I remove the visibility condition "request_path" from the block with label "[TEST] Updated Block Label"
  Then the block with label "[TEST] Updated Block Label" should not have the visibility condition "request_path"

  And I disable the block with label "[TEST] Updated Block Label"
  And the block with label "[TEST] Updated Block Label" is disabled
  And I enable the block with label "[TEST] Updated Block Label"
  And the block with label "[TEST] Updated Block Label" is enabled
