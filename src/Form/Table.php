<?php

namespace Drupal\validator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * This Form checks user data. We extend FormBase.
 *
 * @see \Drupal\Core\Form\FormBase
 */
class Table extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'validator_module';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Count of tables.
    $tab = $form_state->get('tab');
    if ($tab === NULL) {
      $form_state->set('tab', 1);
      $tab = 1;
    }

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('This table make wonderful things'),
    ];
    $form['#attached'] = [
      'library' => 'validator/validator.style',
    ];

    $form['#tree'] = TRUE;
    // Rendering tables.
    for ($k = 1; $k <= $tab; $k++) {
      // Array contains table => row.
      $table[$k] = $form_state->get(['table', $k]);
      if ($table[$k] === NULL) {
        $form_state->set(['table', $k], 1);
        $table[$k] = 1;
      }
      $form['add_fields' . $k] = [
        '#type' => 'submit',
        '#value' => 'Add Year',
        '#name' => $k,
        '#submit' => ['::addRow'],
        '#attributes' => [
          'id' => 'my-row' . $k,
        ],
      ];
      $form['table' . $k] = [
        '#type' => 'table',
        '#header' => [
          $this->t('Year'),
          $this->t('Jan'),
          $this->t('Feb'),
          $this->t('Mar'),
          $this->t('Q1'),
          $this->t('Apr'),
          $this->t('May'),
          $this->t('Jun'),
          $this->t('Q2'),
          $this->t('Jul'),
          $this->t('Aug'),
          $this->t('Sept'),
          $this->t('Q3'),
          $this->t('Oct'),
          $this->t('Nov'),
          $this->t('Dec'),
          $this->t('Q4'),
          $this->t('YTD'),
        ],
        '#attributes' => [
          'class' => [
            'my-table',
          ],
        ],
      ];
      // Rendering rows.
      for ($i = ($form_state->get(['table', $k])); $i > 0; $i--) {
        $form['table' . $k][$i]['#attributes'] = [
          'class' => [
            'foo',
          ],
        ];

        $form['table' . $k][$i]['Years'] = [
          '#type' => 'textfield',
          '#value' => 2020 - $i,
          '#disabled' => TRUE,
        ];
        for ($m = 1; $m <= 12; $m++) {
          $form['table' . $k][$i][$m] = [
            '#type' => 'number',
            '#min' => 0,
            '#step' => 0.01,
            '#attributes' => [
              'class' => ['form-edit-month'],
            ],
          ];
          if ($m % 3 == 0) {
            $form['table' . $k][$i]['Q' . $m] = [
              '#type' => 'number',
              '#step' => 0.01,
              '#attributes' => [
                'class' => [
                  'q1',
                  'quart',
                ],
              ],
            ];
          }
        }
        $form['table' . $k][$i]['YTD'] = [
          '#type' => 'number',
          '#step' => 0.01,
          '#attributes' => [
            'class' => ['ytd'],
          ],
        ];
      }
    }
    $form['actions']['add_table'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Table'),
      '#submit' => ['::addTable'],
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }

  /**
   * Adding new table.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function addTable(array $form, FormStateInterface $form_state) {
    $table_field = $form_state->get('tab');
    $add_table = $table_field + 1;
    $form_state->set('tab', $add_table);
    $form_state->setRebuild();
  }

  /**
   * Adding new rows.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function addRow(array $form, FormStateInterface $form_state) {
    $tab = $form_state->get('tab');
    $position = $form_state->getTriggeringElement();
    for ($k = 1; $k <= $tab; $k++) {
      $row = $form_state->get(['table', $k]);
      if ($k == $position['#name']) {
        $row++;
        $form_state->set(['table', $k], $row);
      }
    }
    $form_state->setRebuild();
  }

  /**
   * Checks the array for a break.
   *
   * @param array $numbers
   *   Inputted values.
   * @param int $prev
   *   Default value.
   *
   * @return bool
   *   True or false.
   */
  public static function check(array $numbers, int $prev = NULL) {
    if (empty($numbers)) {
      return TRUE;
    }
    $current = array_shift($numbers);
    if ($prev == NULL || $current === $prev + 1) {
      return self::check($numbers, $current);
    }
    return FALSE;
  }

  /**
   * Check 2  arrays for diff values.
   * @param array $A
   *   Array first.
   * @param array $B
   *   Array second.
   * @return array
   *   Return array of values
   */
  public function arrayDiff(array $A, array $B) {
    $intersect = array_intersect($A, $B);
    return array_merge(array_diff($A, $intersect), array_diff($B, $intersect));
  }

  /**
   * Checks the array of empty rows for break.
   *
   * @param array $array
   *   Numbers of empty rows.
   * @param int $prev
   *   Default value.
   *
   * @return void True or false.
   *   Return nothing.
   */
  public function row_empty (array $array, $prev = null) {
    if(empty($array)){
      return;
    }
    $curr = array_shift($array);
    if($prev == null) {
      $this->row_empty($array, $curr);
    }
    else {
      if($prev - $curr != 1) {
        global $diff;
        $diff++;
      }
      $this->row_empty($array, $curr);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $submit = $form_state->getTriggeringElement();
    if ($submit['#value'] == 'Submit') {
      $tables = $form_state->get('tab');
      // Get all users input.
      $get_input = $form_state->getValues();
      // Filtering array from quartals and years.
      for ($k = 1; $k <= $tables; $k++) {
        $count_of_tables = $k;
        $values[$k] = $get_input['table' . $k];
        foreach ($values as $rows) {
          foreach ($rows as $row => $value) {
            // Filtering from strings key.
            $clean_data = array_filter($value, function ($element) {
              return is_int($element);
            }, $flag = 2);
            $user_input[$k][$row] = $clean_data;
            // Clean empty values.
            $results = array_filter($user_input[$k][$row], 'strlen');
            if (!empty($results)) {
              $keys = array_keys($results);
              $first_row_element[$k][] = array_shift($keys);
            }
            else {
              $first_row_element[$k][] = 0;
            }
            // Rows validation.
            $valid = self::check(array_keys($results));
            if ($valid) {
              if (1 == count($values[$k])) {
                $form_state->set('status', TRUE);
              }
              else {
                if (array_key_exists(12, $results)
                && array_key_exists(1, $results)) {
                  $form_state->set('status', TRUE);
                  continue;
                }
                if ($row == count($values[$k])) {
                  if($values[$k][$row]['YTD'] == "") {
                    $form_state->set('status', TRUE);
                    $stan[$k][$row] = 'empty';
                    continue;
                  }
                  elseif ($values[$k][$row - 1]['YTD'] != ""
                    && !array_key_exists(12, $results)) {
                    $form_state->set('status', FALSE);
                    return;
                  }
                  else {
                    $form_state->set('status', TRUE);
                    continue;
                  }
                }
                if ($row == 1) {
                  if($values[$k][$row]['YTD'] == "") {
                    $form_state->set('status', TRUE);
                    $stan[$k][$row] = 'empty';
                    continue;
                  }
                  elseif ($values[$k][$row + 1][12] != ""
                  && !array_key_exists(1, $results)) {
                    $form_state->set('status', FALSE);
                    return;
                  }
                  elseif ($values[$k][$row + 1][12] == ""
                    && $values[$k][$row + 1]['YTD'] != "") {
                    $form_state->set('status', FALSE);
                    return;
                  }
                  else {
                    $form_state->set('status', TRUE);
                    continue;
                  }
                }
                print_r($values[$k][$row]);
                if($values[$k][$row]['YTD'] == "") {
                  if ($values[$k][count($values[$k])]['YTD'] != ""
                  && $values[$k][1]['YTD'] != "") {
                    $form_state->set('status', FALSE);
                    return;
                  }
                  if($values[$k][$row+1]['YTD'] != ""
                  && $values[$k][$row-1]['YTD'] != ""){
                    $form_state->set('status', FALSE);
                    return;
                  }
                  $form_state->set('status', TRUE);
                  $stan[$k][$row] = 'empty';
                  continue;
                }
                if($values[$k][$row + 1][12] != ""
                  && !array_key_exists(1, $results)) {
                  $form_state->set('status', FALSE);
                  return;
                }
                if($values[$k][$row - 1][12] != ""
                  && !array_key_exists(12, $results)) {
                  $form_state->set('status', FALSE);
                  return;
                }
                if($values[$k][$row - 1][1] != ""
                  && !array_key_exists(12, $results)) {
                  $form_state->set('status', FALSE);
                  return;
                }
              }
            }
            else {
              $form_state->set('status', FALSE);
              return;
            }
          }
        }
      }
      // Checking for empty rows break.
      if(!empty($stan)) {
        foreach ($stan as $tables) {
          $rows = count($tables);
          global $diff;
          $diff = 0;
          $t_keys = array_keys($tables);
          $this->row_empty($t_keys);
          if ($diff > 1) {
            $form_state->set('status', FALSE);
            return;
          } elseif ($diff == 1 && (array_key_exists(1, $tables) || array_key_exists($rows, $tables))) {
            $form_state->set('status', FALSE);
            return;
          }
        }
      }
      // Checking the same values for all form.
        if ($count_of_tables > 1) {
          for ($i = 2; $i <= $count_of_tables; $i++) {
              $difference = $this->arrayDiff($first_row_element[1], $first_row_element[$i]);
              if (!empty($difference)) {
                if (empty(array_filter($difference))){
                  continue;
                }
                $form_state->set('status', FALSE);
                return;
              }

            }

        }

    }

  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $status = $form_state->get('status');
    if ($status === FALSE) {
      $this->messenger()->addError('Invalid!;(');
    }
    else {
      $this->messenger()->addMessage('Valid^,^');
    }
    $form_state->setRebuild();
  }

}
