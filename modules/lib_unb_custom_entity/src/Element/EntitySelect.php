<?php

namespace Drupal\lib_unb_custom_entity\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Select;

/**
 * Provides entity selection from "select" form element.
 *
 * @see \Drupal\lib_unb_custom_entity\Element\EntityFormOptionsTrait
 *
 * @FormElement("entity_select")
 */
class EntitySelect extends Select {

  use EntityFormOptionsTrait;

  /**
   * {@inheritDoc}
   */
  public function getInfo() {
    return parent::getInfo() + $this->entityInfo() + [
      '#return_type' => 'object',
    ];
  }

  /**
   * Overrides @see \Drupal\Core\Render\Element\Select::preRenderSelect().
   *
   * Populates the element options with entities of the configured type.
   *
   * {@inheritDoc}
   */
  public static function preRenderSelect($element) {
    $element = parent::preRenderSelect($element);
    $element['#options'] += static::buildEntityOptions($element);
    return $element;
  }

  /**
   * Overrides @see \Drupal\Core\Render\Element\Select::processSelect().
   *
   * Populates the element options with entities of the configured type.
   *
   * {@inheritDoc}
   */
  public static function processSelect(&$element, FormStateInterface $form_state, &$complete_form) {
    $element = parent::processSelect($element, $form_state, $complete_form);
    $element['#options'] += static::buildEntityOptions($element);
    return $element;
  }

  /**
   * {@inheritDoc}
   */
  public static function valueCallback(&$element, $input, FormStateInterface $form_state) {
    if ($input = parent::valueCallback($element, $input, $form_state)) {
      if ($element['#return_type'] === 'id') {
        $form_state->setValueForElement($element, $input);
      }
      else {
        $entity = static::entityTypeManager()
          ->getStorage($element['#entity_type'])
          ->load($input);
        $form_state->setValueForElement($element, $entity);
      }
      return $input;
    }
    return parent::valueCallback($element, $input, $form_state);
  }

}
