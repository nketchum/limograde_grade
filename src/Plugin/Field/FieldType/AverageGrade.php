<?php

namespace Drupal\limograde_grade\Plugin\Field\FieldType;

use Drupal\Core\Field\Plugin\Field\FieldType\BooleanItem;

/**
 * Plugin implementation of the 'average_grade' field type.
 *
 * @FieldType(
 *   id = "average_grade",
 *   label = @Translation("Average grade"),
 *   description = @Translation("Display the average grade for approved entities."),
 *   default_widget = "average_grade",
 *   default_formatter = "average_grade"
 * )
 */
class AverageGrade extends BooleanItem {

}
