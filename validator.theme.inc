<?php

/**
 * @file styles for module
 */

/**
 * Implements template_preprocess_HOOK().
 */
function template_preprocess_validator_form(&$variables) {
  $variables['#attached']['library'][] = 'validator/validator_form.css';
}