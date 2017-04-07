<?php

namespace Drupal\limograde_grade\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Grade edit forms.
 *
 * @ingroup limograde_grade
 */
class GradeForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\limograde_grade\Entity\Grade */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created grade.'));
        break;

      default:
        drupal_set_message($this->t('Saved grade.'));
    }
    $form_state->setRedirect('entity.grade.canonical', ['grade' => $entity->id()]);
  }

}
