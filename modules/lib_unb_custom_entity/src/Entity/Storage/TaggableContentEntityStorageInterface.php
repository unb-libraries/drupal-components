<?php

namespace Drupal\lib_unb_custom_entity\Entity\Storage;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Storage handler interface for taggable entity storage classes.
 *
 * @package Drupal\lib_unb_custom_entity
 */
interface TaggableContentEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Retrieve the field that references taxonomy term entities of the given vocabulary ID.
   *
   * @param string $vid
   *   The vocabulary ID.
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface
   *   A field definition.
   */
  public function getTagField($vid);

  /**
   * Retrieve entities which are tagged with a taxonomy term with one of the given names.
   *
   * @param \Drupal\taxonomy\TermInterface[] $tags
   *   An array of taxonomy term entities.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   An array of entity objects.
   */
  public function loadByTags(array $tags);

  /**
   * Retrieve entities which are tagged with a taxonomy term with one of the given names.
   *
   * @param array $names
   *   An array of strings.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   An array of entity objects.
   */
  public function loadByTagNames(array $names);

  /**
   * Retrieve all entities that used to be assigned one of the available tags.
   *
   * @return \Drupal\lib_unb_custom_entity\Entity\TaggableInterface[]
   *   An array of taggable entity objects.
   */
  public function loadRetired();

}
