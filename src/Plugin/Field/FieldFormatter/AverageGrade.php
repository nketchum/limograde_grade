<?php

namespace Drupal\limograde_grade\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\Plugin\Field\FieldFormatter\BooleanFormatter;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Plugin implementation of the 'average_grade' formatter.
 *
 * @FieldFormatter(
 *   id = "average_grade",
 *   label = @Translation("Average grade"),
 *   field_types = {
 *     "average_grade"
 *   }
 * )
 */
class AverageGrade extends BooleanFormatter {

  protected $letters = [
    'A'  => 4,
    'A-' => 3.37,
    'B+' => 3.33,
    'B'  => 3,
    'B-' => 2.67,
    'C+' => 2.33,
    'C'  => 2,
    'C-' => 1.67,
    'D+' => 1.33,
    'D'  => 1,
    'D-' => 0.67,
    'E' => 0
  ];

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    $formats = $this->getOutputFormats();

    foreach ($items as $delta => $item) {
      $format = $this->getSetting('format');

      $entity = $item->getEntity();
      $entity_id = $entity->id();

      // Get list of all grades for this content.
      $query = db_select('grade', 'g');
      $query->join('grade__field_company', 'gfc', 'g.id = gfc.entity_id');
      $query->join('grade__field_grade', 'gfg', 'g.id = gfg.entity_id');
      $query->addField('gfg', 'field_grade_value', 'grade');
      $query->condition('gfc.field_company_target_id', $entity_id, '=');
      $query->condition('g.status', 1, '=');

      $i = 0;
      $total = 0;
      foreach ($query->execute() as $row) {
        if (is_numeric($row->grade)) {
          $total = $total + $row->grade;
          $i++;
        }
      }
      if ($i > 0) {
        $average = $total/$i;
        $letter = self::getLetterGrade($average);
        $entity_link = $entity->toUrl()->toString();
        // $link = Link::fromTextAndUrl($letter, Url::fromUri($entity_link, array()))->toRenderable();
        $elements[$delta] = ['#markup' =>
          '<span class="grade-letter"><strong><a href="'. $entity_link .'">'. $letter .'</a></strong></span> '.
          '<span class="grade-score"><em>(<span class="grade-score-score">'. number_format(round($average, 2), 2) .'/4.00 GPA</span> — <span class="grade-score-count">'. $i .' submitted</span>)</em></span>'];
      }

      // Do not return any data to unpriviliged users if form is unchecked.
      if ((boolean) $item->value === FALSE) {
        if (!\Drupal::currentUser()->hasPermission('always view any average grade') &&
            !\Drupal::currentUser()->hasPermission('always view any own grade')) {
          unset($elements[$delta]);
        }
      }
    }

    return $elements;
  }

  protected function getLetterGrade($score) {
    $grade = '';
    foreach($this->letters as $key => $value) {
      if ($grade === '' && $score >= $value) {
        $grade = $key;
      }
    }
    return $grade;
  }

  /**
   * Gets the available format options.
   *
   * @return array|string
   *   A list of output formats. Each entry is keyed by the machine name of the
   *   format. The value is an array, of which the first item is the result for
   *   boolean TRUE, the second is for boolean FALSE. The value can be also an
   *   array, but this is just the case for the custom format.
   */
  protected function getOutputFormats() {
    $formats = [
      'default' => ['1', '2'],
    ];

    return $formats;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    unset($form['format']);
    unset($form['format_custom_true']);
    unset($form['format_custom_false']);

    $formats = [];
    foreach ($this->getOutputFormats() as $format_name => $format) {
      if (is_array($format)) {
        $formats[$format_name] = $this->t('Default');
      }
      else {
        $formats[$format_name] = $format;
      }
    }

    $field_name = $this->fieldDefinition->getName();
    $form['format'] = [
      '#type' => 'select',
      '#title' => $this->t('Output format'),
      '#default_value' => $this->getSetting('format'),
      '#options' => $formats,
    ];
    $form['format_custom_false'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom output for FALSE'),
      '#default_value' => $this->getSetting('format_custom_false'),
      '#states' => [
        'visible' => [
          'select[name="fields[' . $field_name . '][settings_edit_form][settings][format]"]' => ['value' => 'custom'],
        ],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $setting = $this->getSetting('format');

    $formats = $this->getOutputFormats();
    $summary[] = $this->t('Default');

    return $summary;
  }

}
