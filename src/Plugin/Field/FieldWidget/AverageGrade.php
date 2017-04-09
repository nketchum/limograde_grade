<?php

namespace Drupal\limograde_grade\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\BooleanCheckboxWidget;
use Drupal\Core\Form\FormStateInterface;
// use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'average_grade' widget.
 *
 * @FieldWidget(
 *   id = "average_grade",
 *   label = @Translation("Average grade"),
 *   field_types = {
 *     "average_grade"
 *   },
 *   multiple_values = TRUE
 * )
 */
class AverageGrade extends BooleanCheckboxWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    if (!\Drupal::currentUser()->hasPermission('edit any average grade field') &&
        !\Drupal::currentUser()->hasPermission('edit own average grade field')) {
      $element['#access'] = FALSE;
    }

    return $element;
  }

}
