<?php

namespace Drupal\limograde_grade\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Grade entities.
 *
 * @ingroup limograde_grade
 */
interface GradeInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Grade creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Grade.
   */
  public function getCreatedTime();

  /**
   * Sets the Grade creation timestamp.
   *
   * @param int $timestamp
   *   The Grade creation timestamp.
   *
   * @return \Drupal\limograde_grade\Entity\GradeInterface
   *   The called Grade entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Grade published status indicator.
   *
   * Unpublished Grade are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Grade is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Grade.
   *
   * @param bool $published
   *   TRUE to set this Grade to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\limograde_grade\Entity\GradeInterface
   *   The called Grade entity.
   */
  public function setPublished($published);

}
