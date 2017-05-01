<?php

namespace Drupal\limograde_grade\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for deleting Grade entities.
 *
 * @ingroup limograde_grade
 */
class GradeDeleteForm extends ContentEntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->getEntity();

    // Clear releated company node cache so that grade display updates.
    if (is_array($entity->field_company->getValue())) {
      $node_id = $entity->field_company->getValue()[0]['target_id'];
      if (is_numeric($node_id)) {
        Cache::invalidateTags(array('node:'. $node_id));
      }
    }

    parent::submitForm($form, $form_state);
  }

}
