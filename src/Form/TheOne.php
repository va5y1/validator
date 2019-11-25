<?php

namespace Drupal\Validator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form views class.
 *
 * @see \Drupal\Core\Form\FormBase
 * @see \Drupal\Core\Form\ConfigFormBase
 */
class TheOne extends FormBase {

  /**
   * This var = count of tables.
   *
   * @var int
   */
  protected $table = 1;
  /**
   * This var = count of rows.
   *
   * @var int
   */
  protected $number = 1;
  /**
   * This array is a table header.
   *
   * @var array
   */
  protected $month = [
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'May',
    'Jun',
    'Jul',
    'Aug',
    'Sept',
    'Oct',
    'Nov',
    'Dec',
    'Q4',
  ];

  protected $quartals = [
    'Q1',
    'Q2',
    'Q3',
    'Ytd',
  ];

  protected $headers = [
    'Year',
    'Jan',
    'Feb',
    'Mar',
    'Q1',
    'Apr',
    'May',
    'Jun',
    'Q2',
    'Jul',
    'Aug',
    'Sept',
    'Q3',
    'Oct',
    'Nov',
    'Dec',
    'Q4',
    'YTD',
  ];

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'table_validator';
  }

  /**
   * Build form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Gather the number of names in the form already.
    $tab = $form_state->get('tab');
    // We have to ensure that there is at least one name field.
    if ($tab === NULL) {
      $name_field = $form_state->set('tab', 1);
      $tab = 1;
    }
//    for ($k = 1; $k <= $tab; $k++) {
//      $row[$k] = 1;
//    }
    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('This table make wonderful things'),
    ];

    $form['#tree'] = TRUE;

    for ($k = 1; $k <= $tab; $k++) {
      $table[$k] = $form_state->get(['table', $k]);
      // We have to ensure that there is at least one name field.
      if ($table[$k] === NULL) {
        $name = $form_state->set(['table', $k], 1);
        $table[$k] = 1;
      }
      // $form['table' . $k]['actions'] = [
      //        '#type' => 'actions',
      //      ];
      //      $form['table' . $k]['actions']['add_fields'] = [
      //        '#type' => 'submit',
      //        '#value' => $this->t('Add Year'),
      //        '#submit' => ['::addRow'],
      //      ];
      $form['add_fields' . $k] = [
        '#type' => 'submit',
        '#value' => $this->t('Add Year'. $k),
        '#submit' => ['::addRow'],
        '#attributes' => [
          'id' => [
            'add' . $k,
          ],
        ],
        // '#ajax' => [
        //          'callback' => '::addCallback',
        //          'event' => 'click',
        //        ],
      ];
      $form['table' . $k] = [
        '#type' => 'table',
        '#header' => $this->headers,
        '#attributes' => [
          'class' => [
            'mytable',
          ],
        ],
      ];

      // if($table[$k] == $k){
      //        echo "GOOOOOOOOOOOOOOOD";
      //      }.
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
          '#title' => $this
            ->t("Year"),
          '#title_display' => 'invisible',
        ];

        $form['table' . $k][$i]['Jan'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#ajax' => [
            'callback' => '::Q1Callback',
            'wrapper' => 'edit-outputQ1' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];

        $form['table' . $k][$i]['Feb'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#ajax' => [
            'callback' => '::Q1Callback',
            'wrapper' => 'edit-outputQ1' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];
        $form['table' . $k][$i]['Mar'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#ajax' => [
            'callback' => '::Q1Callback',
            'wrapper' => 'edit-outputQ1' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];
        $form['table' . $k][$i]['Q1'] = [
          '#type' => 'number',
          '#disabled' => TRUE,
          '#default_value' => 0,
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#ajax' => [
            'callback' => '::YTDCallback',
            'wrapper' => 'edit-outputYTD' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
          '#attributes' => [
            'class' => [
              'quartal',
            ],
            'id' => [
              'edit-outputQ1' . $k . 1,
            ],
          ],
        ];
        $form['table' . $k][$i]['Apr'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#ajax' => [
            'callback' => '::Q2Callback',
            'wrapper' => 'edit-outputQ2' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];
        $form['table' . $k][$i]['May'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#ajax' => [
            'callback' => '::Q2Callback',
            'wrapper' => 'edit-outputQ2' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];
        $form['table' . $k][$i]['Jun'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#ajax' => [
            'callback' => '::Q2Callback',
            'wrapper' => 'edit-outputQ2' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];
        $form['table' . $k][$i]['Q2'] = [
          '#type' => 'number',
          '#value' => 0,
          '#disabled' => TRUE,
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#attributes' => [
            'class' => [
              'quartal',
            ],
            'id' => [
              'edit-outputQ2' . $k . 1,
            ],
          ],
          '#ajax' => [
            'callback' => '::YTDCallback',
            'wrapper' => 'edit-outputYTD' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];
        $form['table' . $k][$i]['Jul'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#ajax' => [
            'callback' => '::Q3Callback',
            'wrapper' => 'edit-outputQ3' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];
        $form['table' . $k][$i]['Aug'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#ajax' => [
            'callback' => '::Q3Callback',
            'wrapper' => 'edit-outputQ3' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];
        $form['table' . $k][$i]['Sept'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#ajax' => [
            'callback' => '::Q3Callback',
            'wrapper' => 'edit-outputQ3' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];
        $form['table' . $k][$i]['Q3'] = [
          '#type' => 'number',
          '#value' => 0,
          '#disabled' => TRUE,
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#attributes' => [
            'class' => [
              'quartal',
            ],
            'id' => [
              'edit-outputQ3' . $k . 1,
            ],
          ],
          '#ajax' => [
            'callback' => '::YTDCallback',
            'wrapper' => 'edit-outputYTD' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];
        $form['table' . $k][$i]['Oct'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#ajax' => [
            'callback' => '::Q4Callback',
            'wrapper' => 'edit-outputQ4' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];
        $form['table' . $k][$i]['Nov'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#ajax' => [
            'callback' => '::Q4Callback',
            'wrapper' => 'edit-outputQ4' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];
        $form['table' . $k][$i]['Dec'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#ajax' => [
            'callback' => '::Q4Callback',
            'wrapper' => 'edit-outputQ4' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];
        $form['table' . $k][$i]['Q4'] = [
          '#type' => 'number',
          '#value' => 0,
          '#disabled' => FALSE,
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#attributes' => [
            'class' => [
              'quartal',
            ],
//            'id' => [
//              'edit-outputQ4' . $k . 1,
//            ],

          ],
          '#ajax' => [
            'callback' => '::YTDCallback',
            'wrapper' => 'edit-outputYTD' . $k . 1,
            'event' => 'change',
            'progress' => [
              'type' => 'none',
            ],
          ],
        ];
        $form['table' . $k][$i]['YTD'] = [
          '#type' => 'number',
          '#value' => 0,
          '#disabled' => FALSE,
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#attributes' => [
            'class' => [
              'quartal',
            ],
            'id' => [
              'edit-outputYTD' . $k . 1,
            ],
          ],
        ];
      }
      // $form['table' . $k]['actions'] = [
      //        '#type' => 'actions',
      //      ];

      $form['table' . $k]['actions']['add_table'] = [
        '#type' => 'submit',
        '#value' => $this->t('Add Table'),
        '#submit' => ['::addTable'],
         '#ajax' => [
                  'callback' => '::addCallback',
                  'event' => 'click',
                ],
      ];
    }
    // If there is more than one name, add the remove button.
    if ($this->number > 1) {
      $form['table' . $k]['actions']['remove_fields'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove Year'),
        '#submit' => ['::removeCallback'],
        // '#ajax' => [
        //              'callback' => '::addmoreCallback',
        //              'wrapper' => 'names-fieldset-wrapper',
        //            ],
      ];
    }
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
//    dump($form_state);
    return $form;

  }

  /**
   *
   */
  // Public function Q1Callback(array &$form, FormStateInterface $form_state) {
  //
  //    for ($k = 1; $k <= $this->table; $k++) {
  //      for (1 = 0; 1 < $this->number; 1++) {
  //        $sum = $form_state->getValue([
  //          'table' . $k,
  //          1,
  //          'Jan',
  //        ]) + $form_state->getValue([
  //          'table' . $k,
  //          1,
  //          'Feb',
  //        ]) + $form_state->getValue([
  //          'table' . $k,
  //          1,
  //          'Mar',
  //        ]);
  //        $out = round(($sum + 1) / 3, 2);
  //        $form['table' . $k][1]['Q1']['#value'] = $out;
  //        return $form['table' . $k][1]['Q1'];
  //      }
  //    }
  //  }
  //
  //  /**
  //   *
  //   */
  //  public function Q2Callback(array &$form, FormStateInterface $form_state) {
  //    $sum = $form_state->getValue([
  //      'table1',
  //      0,
  //      'Jan',
  //    ]) + $form_state->getValue([
  //      'table1',
  //      0,
  //      'Feb',
  //    ]) + $form_state->getValue(['table1', 0, 'Mar']);
  //    $out = round(($sum + 1) / 3, 2);
  //    for ($k = 1; $k <= $this->table; $k++) {
  //      for (1 = 0; 1 < $this->number; 1++) {
  //        $form['table' . $k][1]['Q2']['#value'] = $out;
  //        return $form['table' . $k][1]['Q2'];
  //      }
  //    }
  //  }
  //
  //  /**
  //   *
  //   */
  //  public function Q3Callback(array &$form, FormStateInterface $form_state) {
  //    $sum = 5;
  //    for ($k = 1; $k <= $this->table; $k++) {
  //      for (1 = 0; 1 < $this->number; 1++) {
  //        $form['table' . $k][1]['Q3']['#value'] = 2;
  //        return $form['table' . $k][1]['Q3'];
  //
  //      }
  //    }
  //  }
  //
  //  /**
  //   *
  //   */
  //  public function Q4Callback(array &$form, FormStateInterface $form_state) {
  //    $sum = 5;
  //    for ($k = 1; $k <= $this->table; $k++) {
  //      for (1 = 0; 1 < $this->number; 1++) {
  //        $form['table' . $k][1]['Q4']['#value'] = 3;
  //        return $form['table' . $k][1]['Q4'];
  //
  //      }
  //    }
  //  }
  //
  //  /**
  //   *
  //   */
  //  public function YTDCallback(array &$form, FormStateInterface $form_state) {
  //    $sum = 5;
  //    for ($k = 1; $k <= $this->table; $k++) {
  //      for (1 = 0; 1 < $this->number; 1++) {
  //        $form['table' . $k][1]['YTD']['#value'] = 3;
  //        return $form['table' . $k][1]['YTD'];
  //      }
  //    }
  //  }.

  /**
   * Callback for ajax-enabled buttons.
   */
  public function addCallback(array &$form, FormStateInterface $form_state) {
    return $form['table1'];
  }

  /**
   * @todo comments.
   */
  public function addRow(array $form, FormStateInterface $form_state) {
//    $triggeringElement = $form_state->getTriggeringElement();
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
  public function addTable(array $form, FormStateInterface $form_state) {
    $name_field = $form_state->get('tab');
    $add_button = $name_field + 1;
    $form_state->set('tab', $add_button);
    $form_state->setRebuild();

  }

  /**
   * @todo comments.
   */
  public function removeCallback(array &$form, FormStateInterface $form_state) {
    if ($this->number > 1) {
      $this->number--;
    }
    $form_state->setRebuild();
  }

  /**
   *
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // If (strlen($form_state->getValue(['table1', '0', 'Jan']) < 1)) {
    //      $form_state->setErrorByName('table1][0][Jan', $this->t('The phone number is too short. Please enter a full phone number.'));
    //    }.
  }

  /**
   * Final submit handler.
   *
   * Reports what values were finally set.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    for ($k = 1; $k <= $this->table; $k++) {
      // For (1 = 0; 1 < $this->number; 1++) {
      //        $values = $form_state->getValue(['table' . $k, 1]);
      //
      //        $output = $this->t('picnic: @names', [
      //          '@names' => implode(', ', $values),
      //        ]
      //        );
      //        $this->messenger()->addMessage($output);
      //        $form['table' . $k][1]['YTD']['#value'] = 4;
      //      }.
    }

  }

}
