<?php


namespace Drupal\gizra_test\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\og\Plugin\Field\FieldFormatter\GroupSubscribeFormatter;

/**
 * Plugin implementation for the OG subscribe formatter.
 *
 * @FieldFormatter(
 *   id = "gizra_test_group_subscribe",
 *   label = @Translation("Personalized OG Group subscribe"),
 *   description = @Translation("Display personalized OG Group subscribe and un-subscribe links."),
 *   field_types = {
 *     "og_group"
 *   }
 * )
 */
class GroupPersonalSubscribeFormatter extends GroupSubscribeFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    $group = $items->getEntity();
    /** @var \Drupal\user\UserInterface $user */
    $user = $this->entityTypeManager->load(($this->currentUser->id()));
    /** @var \Drupal\Core\Access\AccessResult $access */
    if ($user->isAuthenticated()
      && ($access = $this->ogAccess->userAccess($group, 'subscribe without approval', $user))
      && $access->isAllowed()
      && $elements['0']['#type'] === 'link') {
      $elements[0]['#title'] = $this->t(
        'Hi @name, click here if you would like to subscribe to this group called @group',
        ['@name' => $user->getDisplayName(), '@group' => $group->label()]);
      $elements['#cache']['contexts'] = ['user'];
    }
    return $elements;
  }

}
