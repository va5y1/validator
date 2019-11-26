<?php

/**
 * @file
 * Styles for module.
 */
/**
 * Implements template_preprocess_HOOK().
 */
function template_preprocess_table_module(&$variables)
{
    $variables['#attached']['library'][] = 'validator/validator_form.css';
}
