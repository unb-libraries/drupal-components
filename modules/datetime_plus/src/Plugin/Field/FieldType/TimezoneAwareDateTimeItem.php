<?php

namespace Drupal\datetime_plus\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinitionInterface;
use Drupal\Core\TypedData\TypedDataInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItem;
use Drupal\datetime_plus\Datetime\TimezoneAwareDateTimeComputed;
use Drupal\datetime_plus\Plugin\TimeZoneResolver\DateTimeZoneResolverTrait;

/**
 * Plugin implementation of the 'datetime_plus' field type.
 *
 * @FieldType(
 *   id = "datetime_timezone",
 *   label = @Translation("Date (custom timezone)"),
 *   description = @Translation("Create and store timezone-customizable date values."),
 *   default_widget = "datetime_default",
 *   default_formatter = "datetime_default",
 *   list_class = "\Drupal\datetime\Plugin\Field\FieldType\DateTimeFieldItemList",
 *   constraints = {"DateTimeFormat" = {}}
 * )
 */
class TimezoneAwareDateTimeItem extends DateTimeItem {

  use DateTimeZoneResolverTrait;

  /**
   * {@inheritDoc}
   */
  public function __construct(DataDefinitionInterface $definition, $name = NULL, TypedDataInterface $parent = NULL) {
    // @todo Replace by proper dependency injection once FieldType plugins support it.
    self::$dateTimeZoneResolverManager = \Drupal::service('plugin.manager.timezone_resolver');
    parent::__construct($definition, $name, $parent);
  }

  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = parent::propertyDefinitions($field_definition);

    $properties['date']
      ->setClass(TimezoneAwareDateTimeComputed::class)
      ->setSetting('timezone', static::resolve($field_definition
        ->getSetting('timezone')));

    return $properties;
  }

  /**
   * {@inheritDoc}
   */
  public static function defaultFieldSettings() {
    return [
      'timezone' => 'user',
    ] + parent::defaultFieldSettings();
  }

}
