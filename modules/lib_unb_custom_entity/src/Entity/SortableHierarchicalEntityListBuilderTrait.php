<?php

namespace Drupal\lib_unb_custom_entity\Entity;

/**
 * Trait to render a list of entities as a hierarchically sorted list.
 *
 * @package Drupal\lib_unb_custom_entity\Entity
 */
trait SortableHierarchicalEntityListBuilderTrait {

  /**
   * Build a prefix for row rendering the given location.
   *
   * @param \Drupal\lib_unb_custom_entity\Entity\HierarchicalInterface $entity
   *   A hierarchical entity.
   * @param string $prefix
   *   A character sequence to use as row prefix.
   *
   * @return string
   *   A sequence of the given prefix if the given entity has one or more
   *   parents. An empty string for root entities.
   */
  protected function buildRowPrefix(HierarchicalInterface $entity, string $prefix = '–––') {
    if ($superior = $entity->getSuperior()) {
      return $prefix . $this->buildRowPrefix($superior);
    }
    return '';
  }

  /**
   * {@inheritDoc}
   */
  public function sortKeys() {
    return [
      SortableHierarchicalInterface::FIELD_SORT_KEY,
    ];
  }

}
