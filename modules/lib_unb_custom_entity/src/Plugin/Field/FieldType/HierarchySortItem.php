<?php

namespace Drupal\lib_unb_custom_entity\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\StringItem;
use Drupal\entity_hierarchy\Entity\SortableHierarchicalInterface;

/**
 * Defines the 'hierarchy_sort' entity field type.
 *
 * @FieldType(
 *   id = "hierarchy_sort",
 *   label = @Translation("Hierarchy sort"),
 *   description = @Translation("A string value indicating a hierarchy sort order."),
 *   no_ui = TRUE,
 *   cardinality = 1,
 *   default_widget = "string_textfield",
 *   default_formatter = "string"
 * )
 */
class HierarchySortItem extends StringItem {

  protected const FIELDS = 'fields';
  protected const DELIMITER = 'delimiter';
  protected const FILL = 'fill';
  protected const CHUNK_SIZE = 'chunk_size';

  /**
   * {@inheritDoc}
   */
  public static function defaultStorageSettings() {
    return [
      self::CHUNK_SIZE => 5,
      self::FIELDS => [
        'id',
      ],
      self::DELIMITER => '',
      self::FILL => '#',
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritDoc}
   */
  public function preSave() {
    parent::preSave();
    $this->applyDefaultValue();
  }

  /**
   * {@inheritDoc}
   */
  public function applyDefaultValue($notify = TRUE) {
    parent::applyDefaultValue($notify);

    $sort_key = $this->buildSortKey();
    $entity = $this->getEntity();

    if ($entity instanceof SortableHierarchicalInterface && $parent = $entity->getSuperior()) {
      if (!$parent->getSortKey()) {
        $parent->get(SortableHierarchicalInterface::FIELD_SORT_KEY)
          ->applyDefaultValue();
        $parent->save();
      }
      $sort_key = $parent->getSortKey() . $this->getDelimiter() . $sort_key;
    }
    $this->setValue(['value' => $sort_key], $notify);
    return $this;
  }

  /**
   * The delimiter that separates parts of the hierarchical sort key.
   *
   * @return string
   *   A string.
   */
  protected function getDelimiter() {
    return $this->getFieldDefinition()
      ->getSetting(self::DELIMITER);
  }

  /**
   * Build a sort key for the entity to which the computed field is attached.
   *
   * @return string
   *   A string.
   */
  protected function buildSortKey() {
    $length = $this->getChunkSize();
    $base_field_value = substr($this->getBaseFieldValue(), 0, $length);

    return strtoupper(str_pad($base_field_value, $length + 1, $this->getFill(), STR_PAD_RIGHT));
  }

  /**
   * Retrieve the minimum length the local sort key must have.
   *
   * @return int
   *   An integer >= 0.
   */
  protected function getChunkSize() {
    return $this->getFieldDefinition()
      ->getSetting(self::CHUNK_SIZE);
  }

  /**
   * Retrieve the character(s) used as fill a chunk to its minimum size.
   *
   * @return string
   *   A string.
   */
  protected function getFill() {
    return $this->getFieldDefinition()
      ->getSetting(self::FILL);
  }

  /**
   * Retrieve the value of the base field.
   *
   * @param string $delimiter
   *   A string.
   *
   * @return string
   *   A string. If the base field is a multi-value field, all field values
   *   will be concatenated.
   */
  protected function getBaseFieldValue($delimiter = '') {
    $values = [];

    $item_list = $this->getEntity()->get($this->getBaseFieldName());
    foreach ($item_list as $item) {
      $value = $item->getValue();
      $key = array_keys($value)[0];
      $values[] = $value[$key];
    }

    return implode($delimiter, $values);

  }

  /**
   * Retrieve the name of the field that the sort key should be based on.
   *
   * @return string
   *   A string.
   */
  protected function getBaseFieldName() {
    return $this->getFieldDefinition()
      ->getSetting(self::FIELDS)[0];
  }

}
