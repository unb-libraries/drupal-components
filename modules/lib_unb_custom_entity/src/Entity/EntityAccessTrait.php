<?php

namespace Drupal\lib_unb_custom_entity\Entity;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;

trait EntityAccessTrait {

  public function access($operation, AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var \Drupal\Core\Access\AccessResultInterface $access */
    $access = parent::access($operation, $account, $return_as_object);
    if (!$access->isForbidden()) {
      /** @var \Drupal\Core\Entity\EntityInterface $this */
      $entity_type_id = $this->getEntityTypeId();
      $permission = "$operation $entity_type_id entities";
      $access = AccessResult::allowedIfHasPermission($account, $permission);
    }
    return $access;
  }
}