<?php

/**
 * @file
 * Contains grade.page.inc.
 *
 * Page callback for Grade entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Grade templates.
 *
 * Default template: grade.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_grade(array &$variables) {
  // Fetch Grade Entity Object.
  $grade = $variables['elements']['#grade'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
