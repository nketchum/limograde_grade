<?php

namespace Drupal\limograde_grade;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilder;

/**
 * View builder handler for nodes.
 */
class GradeViewBuilder extends EntityViewBuilder {

  /**
   * {@inheritdoc}
   */
  protected function alterBuild(array &$build, EntityInterface $entity, EntityViewDisplayInterface $display, $view_mode) {
    /** @var \Drupal\limograde_grade\GradeInterface $entity */
    parent::alterBuild($build, $entity, $display, $view_mode);
    if ($entity->id()) {
      $build['#contextual_links']['grade'] = [
        'route_parameters' => ['grade' => $entity->id()],
        'metadata' => ['changed' => $entity->getChangedTime()],
      ];
    }
  }

}
