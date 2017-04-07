<?php

namespace Drupal\limograde_grade;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Grade entity.
 *
 * @see \Drupal\limograde_grade\Entity\Grade.
 */
class GradeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\limograde_grade\Entity\GradeInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished grade entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published grade entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit grade entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete grade entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add grade entities');
  }

}
