<?php

namespace Drupal\Tests\ExistingSite;

use weitzman\DrupalTestTraits\ExistingSiteBase;

/**
 * Test gizra_test module.
 */
class GroupPersonalSubscribeTest extends ExistingSiteBase {

  /**
   * Test personal subscribe link.
   */
  public function testLink() {
    $test_user = $this->createUser([], 'Chipmunk', FALSE);
    $node = $this->createNode([
      'title' => 'Pony',
      'type' => 'group',
      'uid' => 1,
    ]);
    $node->setPublished()->save();
    // Check if correct message and link displayed for authenticated user.
    $this->drupalLogin($test_user);
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Hi Chipmunk, click here if you would like to subscribe to this group called Pony');
    $this->clickLink('Hi Chipmunk, click here if you would like to subscribe to this group called Pony');
    $this->assertSession()->pageTextContains('Are you sure you want to join the group Pony?');
    $this->drupalLogout();
    // Check no personal message is displayed to anonymous.
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Subscribe to group');
    // Check personal message is not cached between users.
    $test_user_2 = $this->createUser([], 'Beaver', FALSE);
    $this->drupalLogin($test_user_2);
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Hi Beaver, click here if you would like to subscribe to this group called Pony');
  }

}
