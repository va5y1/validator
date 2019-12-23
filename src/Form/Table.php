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
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $submit = $form_state->getTriggeringElement();
    if ($submit['#value'] == 'Submit') {
      $tables = $form_state->get('tab');
      // Get all users input.
      $get_input = ($form_state->getValues());
      // Filtering array from quartals and years.
      for ($k = 1; $k <= $tables; $k++) {
        $count_of_tables = $k;
        $values[$k] = $get_input['table' . $k];
        foreach ($values as $rows) {
          foreach ($rows as $row => $value) {
            // If row is empty than its valid:
            if ($value['YTD'] != "") {
              // Filtering from strings key.
              $clean_data = array_filter($value, function ($element) {
                return is_int($element);
              }, $flag = 2);
              $user_input[$k][$row] = $clean_data;
              // Clean empty values.
              $results = array_filter($user_input[$k][$row], 'strlen');
              // Rows validation.
              $valid = self::check(array_keys($results));
              // якщо рядок не останній і має 12 місяць і валідний,
              // то продовжуємо перевіряти далі:
              if (array_key_exists(12, $results) && $valid && $row != 1) {
                $stan[$k][$row] = TRUE;
              }
              // якщо валідний, не останній, не перший, не має 12 місяця
              // і не має поруч заповнені рядки, значить підходить:
              elseif ($valid
                && $row != 1
                && $row != count($values[$k])
                && ($values[$k][$row - 1]['YTD'] == "")
                && ($values[$k][$row + 1]['YTD'] == "")) {
                $stan[$k][$row] = TRUE;
              }
              // якщо валідний, не останній, не перший але не має 12 місяця
              // і має поруч заповнені рядки, значить не підходить:
              elseif ($valid && $row != 1 && $row != count($values[$k])) {
                $stan[$k][$row] = FALSE;
                $form_state->set('status', FALSE);
                return;
              }
              // якщо не має 12 місяць але має наступний заповнений ряд:
              elseif ($row > 1 && $valid && ($values[$k][$row - 1]['YTD'] != "")) {
                $stan[$k][$row] = FALSE;
                $form_state->set('status', FALSE);
                return;
              }
              // якщо все інше не спрацювало отже весь ряд валідний:
              elseif ($valid) {
                $stan[$k][$row] = TRUE;
              }
              else {
                $stan[$k][$row] = FALSE;
                $form_state->set('status', FALSE);
                return;
              }
            }
            else {
              $stan[$k][$row] = TRUE;
            }

          }
        }
      }
      // загальна валідність для кожної форми:
      $table = [];
      foreach ($stan as $tables) {
        $rows = count($tables);
        foreach ($tables as $row => $key) {
          if ($key && $row != $rows) {
            continue;
          }
          elseif ($key && count($stan) == 1) {
            $form_state->set('status', TRUE);
          }
          elseif ($key) {
            $table[] = TRUE;
          }
          else {
            $table[] = FALSE;
          }
        }
      }
      // перевірка на валідність всіх форм разом:
      if (in_array(FALSE, $table)) {
        $form_state->set('status', FALSE);
      }
      else {
        if ($count_of_tables > 1) {
          for ($i = 1; $i < $count_of_tables; $i++) {
            foreach ($values[$i] as $key => $rows) {
              if (!isset($values[1][$key]) || !isset($values[$i + 1][$key])) {
                // $form_state->set('status', TRUE);
                continue;
              }
              elseif (!isset($user_input[1][$key]) || !isset($user_input[$i + 1][$key])) {
                // $form_state->set('status', TRUE);
                continue;
              }
              elseif (count(array_filter($user_input[1][$key], 'strlen')) != count(array_filter($user_input[$i + 1][$key]))) {
                // $c = arry_afilter($userInput[1][$key], 'strlen');
                // $d = array_filter($userInput[$i + 1][$key], 'strlen');
                $form_state->set('status', FALSE);
                break;
              }
              else {
                $form_state->set('status', TRUE);
              }

            }
          }

        }
        else {
          $form_state->set('status', TRUE);
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
