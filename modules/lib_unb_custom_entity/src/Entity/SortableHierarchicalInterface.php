<?php

namespace Drupal\lib_unb_custom_entity\Entity;

/**
 * Interface for entities that shape a hierarchical relationship with each other.
 *
 * @package Drupal\lib_unb_custom_entity\Entity
 */
interface SortableHierarchicalInterface extends HierarchicalInterface {

  const FIELD_SORT_KEY = 'sort_key';

  /**
   * Retrieve the sort value that positions the entity within the hierarchy.
   *
   * @return string
   *   A string.
   */
  public function getSortKey();

}