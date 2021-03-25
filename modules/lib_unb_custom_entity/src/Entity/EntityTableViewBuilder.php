<?php

namespace Drupal\lib_unb_custom_entity\Entity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilder;

/**
 * View builder to render entities in a table format.
 *
 * @package Drupal\lib_unb_custom_entity\Entity
 */
class EntityTableViewBuilder extends EntityViewBuilder {

  /**
   * {@inheritDoc}
   */
  protected function getBuildDefaults(EntityInterface $entity, $view_mode) {
    $build = parent::getBuildDefaults($entity, $view_mode);
    $build['#theme_wrappers'][] = 'entity_table';
    return $build;
  }

  /**
   * {@inheritDoc}
   */
  protected function alterBuild(array &$build, EntityInterface $entity, EntityViewDisplayInterface $display, $view_mode) {
    parent::alterBuild($build, $entity, $display, $view_mode);
    foreach ($display->getComponents() as $name => $options) {
      if (isset($build[$name])) {
        $build[$name]['#label_display'] = 'hidden';
      }
    }
  }

}
