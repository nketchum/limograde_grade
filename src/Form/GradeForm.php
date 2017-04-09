<?php

namespace Drupal\limograde_grade\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\ContentEntityInterface;
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
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $form['#entity_builders']['update_status'] = '::updateStatus';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $element = parent::actions($form, $form_state);
    $grade = $this->entity;

    // Add a "Publish" button.
    $element['publish'] = $element['submit'];
    // If the "Publish" button is clicked, we want to update the status to "published".
    $element['publish']['#published_status'] = TRUE;
    if (\Drupal::currentUser()->hasPermission('edit grade entities') && !$grade->isNew()) {
      $element['publish']['#dropbutton'] = 'save';
    }
    if ($grade->isNew()) {
      $element['publish']['#value'] = t('Submit and approve');
    }
    else {
      $element['publish']['#value'] = $grade->isPublished() ? t('Save and keep approved') : t('Save and approve');
    }
    $element['publish']['#weight'] = 0;

    // Add a "Unpublish" button.
    $element['unpublish'] = $element['submit'];
    // If the "Unpublish" button is clicked, update the status to "unpublished".
    $element['unpublish']['#published_status'] = FALSE;
    if (\Drupal::currentUser()->hasPermission('edit grade entities') && !$grade->isNew()) {
      $element['unpublish']['#dropbutton'] = 'save';
    }
    if ($grade->isNew()) {
      $element['unpublish']['#value'] = t('Submit grade');
    }
    else {
      $element['unpublish']['#value'] = !$grade->isPublished() ? t('Save and approve') : t('Save as unapproved');
    }
    $element['unpublish']['#weight'] = 10;

    // If already published, the 'publish' button is primary.
    if ($grade->isPublished()) {
      unset($element['unpublish']['#button_type']);
    }
    // Otherwise, the 'unpublish' button is primary and should come first.
    else {
      unset($element['publish']['#button_type']);
      $element['unpublish']['#weight'] = -10;
    }

    // Remove the "Save" button.
    $element['submit']['#access'] = FALSE;

    $element['delete']['#access'] = $grade->access('delete');
    $element['delete']['#weight'] = 100;

    return $element;
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

    // Clear releated company node cache so that grade display updates.
    if (array_key_exists('field_company', $form)) {
      if (is_numeric($form['field_company']['widget'][0]['target_id']['#value'])) {
        $node_id = $form['field_company']['widget'][0]['target_id']['#value'];
      }
      elseif (is_object($form['field_company']['widget'][0]['target_id']['#default_value'])) {
        $node = $form['field_company']['widget'][0]['target_id']['#default_value'];
        $node_id = $node->id();
      }
      Cache::invalidateTags(array('node:'. $node_id));
    }
  }

  /**
   * Entity builder updating the node status with the submitted value.
   *
   * @param string $entity_type_id
   *   The entity type identifier.
   * @param \Drupal\limograde_grade\GradeInterface $entity
   *   The node updated with the submitted values.
   * @param array $form
   *   The complete form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @see \Drupal\node\NodeForm::form()
   */
  public function updateStatus($entity_type_id, ContentEntityInterface $entity, array $form, FormStateInterface $form_state) {
    $element = $form_state->getTriggeringElement();
    if (isset($element['#published_status'])) {
      $entity->setPublished($element['#published_status']);
    }
  }

}
