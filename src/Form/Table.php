<?php

namespace Drupal\Validator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *
 */
class Table extends FormBase
{

    /**
     * Returns a unique string identifying the form.
     *
     * @return string
     *   The unique string identifying the form.
     */
    public function getFormId()
    {
        return 'table_module';
    }

    /**
     * Form constructor.
     *
     * @param array                                $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     *
     * @return array
     *   The form structure.
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        // Кількість таблиць у формі по дефолту.
        $tab = $form_state->get('tab');
        // Переконуємось що як мінімум одна.
        if ($tab === null) {
            $tab_field = $form_state->set('tab', 1);
            $tab = 1;
        }

        $form['description'] = [
        '#type' => 'item',
        '#markup' => $this->t('This table make wonderful things'),
        ];

        $form['#tree'] = true;
        // Цикл відповідає за кількість таблиць.
        for ($k = 1; $k <= $tab; $k++) {
            // Масив зберігає значення таблиця => кількість рядків.
            $table[$k] = $form_state->get(['table', $k]);
            // Перевіряємо щоб дефолтне значення рядків було 1.
            if ($table[$k] === null) {
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
                '#disabled' => true,
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
                'class' => [
                'q1',
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
                'class' => [
                'q2',
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
                'class' => [
                'q3',
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
                'class' => [
                'q4',
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
    public function addTable(array $form, FormStateInterface $form_state)
    {
        $table_field = $form_state->get('tab');
        $add_table = $table_field + 1;
        $form_state->set('tab', $add_table);
        $form_state->setRebuild();
    }

    /**
     * Adding new rows.
     */
    public function addRow(array $form, FormStateInterface $form_state)
    {
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
    public function addTableCallback(array &$form, FormStateInterface $form_state)
    {
        return $form;
    }

    /**
     *
     */
    public function addRowCallback(array &$form, FormStateInterface $form_state)
    {
        return $form['table1'];
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if ($_POST['op'] == 'Submit') {
            /**
             * @param array $numbers
             * @param null  $prev
             *
             * @return bool
             */
            function check(array $numbers, $prev = null)
            {
                if (empty($numbers)) {
                    return true;
                }
                $current = array_shift($numbers);
                if ($prev == null || $current === $prev + 1) {
                    return check($numbers, $current);
                }
                return false;
            }
            $tables = $form_state->get('tab');
            //отримуємо всі інпути:
            $a = ($form_state->getValues());
            $countOfTables = 0;
            $countOfRows = 0;
            //перебір всіх значень і виключення квартальних і річних сум:
            for ($k = 1; $k <= $tables; $k++) {
                $countOfTables++;
                $values[$k] = $a['table' . $k];
                foreach ($values as $rows) {
                    foreach ($rows as $row => $value) {
                        if ($value['YTD'] != '') {
                            foreach ($value as $number => $input) {
                                if (($number == 'Q1')
                                    || ($number == 'Q2')
                                    || ($number == 'Q3')
                                    || ($number == 'Q4')
                                    || ($number == 'YTD')
                                    || ($number == 'Years')
                                ) {
                                    continue;
                                }
                                  $userInput[$k][$row][$number] = $input;

                            }
                            //видалення комірок з пустими значеннями:
                            $results = array_filter($userInput[$k][$row], 'strlen');
                            //валідація рядків:
                            $valid = check(array_keys($results));
                            //якщо рядок не останній і має 12 місяць і валідний,
                            //то продовжуємо перевіряти далі:
                            if (array_key_exists(12, $results) && $valid && $row != 1) {
                                continue;
                                //якщо валідний, не останній, не перший але не
                                // має 12 місяця, значить не підходить:
                            } elseif ($valid && $row != 1 && $row != count($values[$k])) {
                                $stan[$k][$row] = false;
                                //якщо все інше не спрацювало отже весь ряд валідний:
                            } elseif ($valid) {
                                $stan[$k][$row] = true;
                            } else {
                                $stan[$k][$row] = false;
                            }
                        } else { //якщо рядок пустий то він валідний:
                            $stan[$k][$row] = true;
                        }

                    }
                }
            }
            //загальна валідність для кожної форми:
            $table = [];
            foreach ($stan as $tables) {
                $rows = count($tables);
                foreach ($tables as $row => $key) {
                    if ($key && $row != $rows) {
                        continue;
                    } elseif ($key && count($stan) == 1) {
                        $form_state->set('status', true);
                    } elseif ($key) {
                        $table[] = true;
                    } else {
                        $table[] = false;
                    }
                }
            }
            //перевірка на валідність всіх форм разом:
            if(in_array(false, $table)) {
                $form_state->set('status', false);
            } else {
                $form_state->set('status', true);
            }
            /*
             * Якщо в формі один рік,
             *  у всіх формах повинні бути заповненні значення
             *  за однаковий період:
             */
            for ($k = 1; $k <= $countOfTables; $k++) {
                $countOfRows += count($values[$k]);
            }
            if ($countOfTables == $countOfRows) {
                if ($countOfTables > 1) {
                    for ($i = 2; $i <= $countOfTables; $i++) {
                        $diff = array_diff_assoc($values[$i - 1][1], $values[$i][1]);
                    }
                    if (empty($diff)) {
                          $form_state->set('status', true);
                    } else {
                         $form_state->set('status', false);
                    }
                }

            }
        }

    }

    /**
     * Form submission handler.
     *
     * @param array                                $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     */
    public
    function submitForm(array &$form, FormStateInterface $form_state)
    {
        $status = $form_state->get('status');
        if ($status === false) {
            $this->messenger()->addError('Invalid!;(');
        } elseif ($status === true) {
            $this->messenger()->addMessage('Valid:!');
        } else {
            $this->messenger()->addWarning(
                'I don`t know what to do
             in this situation('
            );

        }
        $form_state->setRebuild();
    }

}
