<?php

namespace Drupal\Validator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *
 */
class Table extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'table_module';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Кількість таблиць у формі по дефолту.
    $tab = $form_state->get('tab');
    // Переконуємось що як мінімум одна.
    if ($tab === NULL) {
      $tab_field = $form_state->set('tab', 1);
      $tab = 1;
    }

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('This table make wonderful things'),
    ];

    $form['#tree'] = TRUE;
    // Цикл відповідає за кількість таблиць.
    for ($k = 1; $k <= $tab; $k++) {
      // Масив зберігає значення таблиця => кількість рядків.
      $table[$k] = $form_state->get(['table', $k]);
      // Перевіряємо щоб дефолтне значення рядків було 1.
      if ($table[$k] === NULL) {
        $row = $form_state->set(['table', $k], 1);
        $table[$k] = 1;
      }
      $form['add_fields' . $k] = [
        '#type' => 'submit',
        '#value' => 'Add Year' . $k,
        '#submit' => ['::addRow'],
      // '#ajax' => [
      //          'callback' => '::addRowCallback',
      //          'event' => 'click',
      //          'wrapper' => 'table' . $k,
      //        ],
      ];
      $form['table' . $k] = [
        '#type' => 'table',
        '#header' => [
          t('Year'),
          t('Jan'),
          t('Feb'),
          t('Mar'),
          t('Q1'),
          t('Apr'),
          t('May'),
          t('Jun'),
          t('Q2'),
          t('Jul'),
          t('Aug'),
          t('Sept'),
          t('Q3'),
          t('Oct'),
          t('Nov'),
          t('Dec'),
          t('Q4'),
          t('YTD'),
        ],
        '#attributes' => [
          'class' => [
            'mytable',
          ],
          'id' => 'table' . $k,
        ],
      ];

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
        for ($m = 1; $m < 4; $m++) {
          $form['table' . $k][$i][$m] = [
            '#type' => 'number',
            '#min' => 0,
            '#step' => 0.01,
            '#attributes' => [
              'class' => ['form-edit-month'],
            ],
          ];
        }
        $form['table' . $k][$i]['Q1'] = [
          '#type' => 'number',
          '#step' => 0.01,
          '#attributes' => [
            'class' => ['q1',
              'quart',
            ],
          ],
        ];
        for ($m = 4; $m < 7; $m++) {
          $form['table' . $k][$i][$m] = [
            '#type' => 'number',
            '#min' => 0,
            '#step' => 0.01,
            '#attributes' => [
              'class' => ['form-edit-month'],
            ],
          ];
        }
        $form['table' . $k][$i]['Q2'] = [
          '#type' => 'number',
          '#step' => 0.01,
          '#attributes' => [
            'class' => ['q2',
              'quart',
            ],
          ],
        ];
        for ($m = 7; $m < 10; $m++) {
          $form['table' . $k][$i][$m] = [
            '#type' => 'number',
            '#step' => 0.01,
            '#min' => 0,
            '#attributes' => [
              'class' => ['form-edit-month'],
            ],
          ];
        }
        $form['table' . $k][$i]['Q3'] = [
          '#type' => 'number',
          '#step' => 0.01,
          '#attributes' => [
            'class' => ['q3',
              'quart',
            ],
          ],
        ];
        for ($m = 10; $m < 13; $m++) {
          $form['table' . $k][$i][$m] = [
            '#type' => 'number',
            '#step' => 0.01,
            '#min' => 0,
            '#attributes' => [
              'class' => ['form-edit-month'],
            ],
          ];
        }
        $form['table' . $k][$i]['Q4'] = [
          '#type' => 'number',
          '#step' => 0.01,
          '#attributes' => [
            'class' => ['q4',
              'quart',
            ],
          ],
        ];
        $form['table' . $k][$i]['YTD'] = [
          '#type' => 'number',
          '#step' => 0.01,
          '#attributes' => [
            'class' => ['ytd'],
          ],
        ];
      } //end for $i
    } //end for $k
    $form['actions']['add_table'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Table'),
      '#submit' => ['::addTable'],
      // '#ajax' => [
      //            'callback' => '::addTableCallback',
      //            'event' => 'click',
      //            'wrapper' => 'table-module',
      //          ],
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }

  /**
   * Adding new table.
   */
  public function addTable(array $form, FormStateInterface $form_state) {
    $table_field = $form_state->get('tab');
    $add_table = $table_field + 1;
    $form_state->set('tab', $add_table);
    $form_state->setRebuild();
  }

  /**
   * Adding new rows.
   */
  public function addRow(array $form, FormStateInterface $form_state) {
    $tab = $form_state->get('tab');
    $post = substr($_POST['op'], 8);
    for ($k = 1; $k <= $tab; $k++) {
      $row = $form_state->get(['table', $k]);
      if ($k == $post) {
        $row++;
        $form_state->set(['table', $k], $row);
      }
    }
    $form_state->setRebuild();
  }

  /**
   *
   */
  public function addTableCallback(array &$form, FormStateInterface $form_state) {
    return $form;
  }

  /**
   *
   */
  public function addRowCallback(array &$form, FormStateInterface $form_state) {
    return $form['table1'];
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $tables = $form_state->get('tab');
    $a = ($form_state->getValues());
    $countOfTables = 0;
    $countOfRows = 0;
    for ($k = 1; $k <= $tables; $k++) {
      $countOfTables++;
      $values[$k] = $a['table' . $k];
      foreach ($values as $rows) {
        foreach ($rows as $row => $value) {
          foreach ($value as $number => $input) {
            if (($number == 'Q1') || ($number == 'Q2') || ($number == 'Q3') || ($number == 'Q4') || ($number == 'YTD') || ($number == 'Years')) {
              continue;
            }
            $userInput[$k][$row][$number] = $input;

          }
        }
      }
    }
    var_dump($values);
    for ($k = 1; $k <= $tables; $k++) {
      $countOfRows += count($userInput[$k]);

    }
    echo $countOfRows;
    echo $countOfTables;
    if ($countOfTables == $countOfRows) {
      $form_state->set('status', TRUE);
    }
    // Echo $countOfRows;
    // echo $countOfTables;.
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
      $form_state->setRebuild();
    }
    elseif ($status === TRUE) {
      $this->messenger()->addMessage('Valid:!');
    }
  }

}
