<?php

namespace Drupal\limograde_grade\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Grade entities.
 */
class GradeViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
