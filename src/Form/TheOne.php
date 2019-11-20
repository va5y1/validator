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
   * This var = count of rows.
   *
   * @var int
   */
  protected $number = 1;

  /**
   * This var = count of tables.
   *
   * @var int
   */
  protected $table = 1;

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

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('This table make wonderful things'),
    ];

    $form['#tree'] = TRUE;

    for ($k = 1; $k <= $this->table; $k++) {
      $form['table' . "$k"]['actions'] = [
        '#type' => 'actions',
      ];
      $form['table' . "$k"]['actions']['add_fields'] = [
        '#type' => 'submit',
        '#value' => $this->t('Add Year'),
        '#submit' => ['::addRow'],
      ];
      $form['table' . "$k"] = [
        '#type' => 'table',
        '#caption' => $this
          ->t('Sample Table'),
        '#header' => $this->headers,
        '#attributes' => [
          'class' => [
            'mytable',
          ],
        ],
      ];

      for ($i = 0; $i < $this->number; $i++) {

        $form['table' . "$k"][$i]['#attributes'] = [
          'class' => [
            'foo',
          ],
        ];

        $form['table' . "$k"][$i]['Years'] = [
          '#type' => 'textfield',
          '#value' => 2019 - $i,
          '#disabled' => TRUE,
          '#title' => $this
            ->t("Year"),
          '#title_display' => 'invisible',
        ];

        $form['table' . "$k"][$i]['Jan'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
          '#ajax' => [
        // don't forget :: when calling a class method.
            'callback' => '::myAjaxCallback',
            // 'callback' => [$this, 'myAjaxCallback'], //alternative notation
        // Or TRUE to prevent re-focusing on the triggering element.
            'event' => 'change',
            'progress' => [
              'type' => 'throbber',
              'message' => $this->t('Verifying entry...'),
            ],
          ],
        ];
        $form['table' . "$k"][$i]['Feb'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['Mar'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['Q1'] = [
          '#type' => 'number',
          '#default_value' => 0,
          '#disabled' => TRUE,
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['Apr'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['May'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['Jun'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['Q2'] = [
          '#type' => 'number',
          '#value' => 0,
          '#disabled' => TRUE,
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['Jul'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['Aug'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['Sept'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['Q3'] = [
          '#type' => 'number',
          '#value' => 0,
          '#disabled' => TRUE,
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['Oct'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['Nov'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['Dec'] = [
          '#type' => 'number',
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['Q4'] = [
          '#type' => 'number',
          '#value' => 0,
          '#disabled' => TRUE,
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];
        $form['table' . "$k"][$i]['Ytd'] = [
          '#type' => 'number',
          '#value' => 0,
          '#disabled' => TRUE,
          '#title' => $this
            ->t("title"),
          '#title_display' => 'invisible',
        ];

      }
      $form['table' . "$k"]['actions'] = [
        '#type' => 'actions',
      ];
      $form['table' . "$k"]['actions']['add_fields'] = [
        '#type' => 'submit',
        '#value' => $this->t('Add Year'),
        '#submit' => ['::addRow'],
        // '#ajax' => [
        //          'callback' => '::addCallback',
        //          'event' => 'click',
        //        ],
      ];
      $form['table' . "$k"]['actions']['add_table'] = [
        '#type' => 'submit',
        '#value' => $this->t('Add Table'),
        '#submit' => ['::addTable'],
        // '#ajax' => [
        //          'callback' => '::addCallback',
        //          'event' => 'click',
        //        ],
      ];

      // If there is more than one name, add the remove button.
      if ($this->number > 1) {
        $form['table' . "$k"]['actions']['remove_fields'] = [
          '#type' => 'submit',
          '#value' => $this->t('Remove Year'),
          '#submit' => ['::removeCallback'],
          // '#ajax' => [
          //              'callback' => '::addmoreCallback',
          //              'wrapper' => 'names-fieldset-wrapper',
          //            ],
        ];
      }

    }
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;

  }

  /**
   *
   */
  public function myAjaxCallback(array &$form, FormStateInterface $form_state) {
    $form_state->setValue(['table1', '0', 'Q1'], 5);
//    $form['table1']['0']['Q1']['#fefault_value'] = 5;
    return $form['table1']['0']['Q1'];
  }

  /**
   * Callback for ajax-enabled buttons.
   */
  public function addCallback(array &$form, FormStateInterface $form_state) {
    return $form['table1'];
  }

  /**
   * @todo comments.
   */
  public function addRow(array &$form, FormStateInterface $form_state) {
    $this->number++;
    $form_state->setRebuild();
  }

  /**
   *
   */
  public function addTable(array $form, FormStateInterface $form_state) {
    $this->table++;
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
    if (strlen($form_state->getValue(['table1', '0', 'Jan']) < 3)) {
      $form_state->setErrorByName('table1][0][Jan', $this->t('The phone number is too short. Please enter a full phone number.'));
    }
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
      for ($i = 0; $i < $this->number; $i++) {
        $values = $form_state->getValue(['table' . "$k", "$i"]);

        $output = $this->t('picnic: @names', [
          '@names' => implode(', ', $values),
        ]
        );
        $this->messenger()->addMessage($output);

      }
    }

  }

}
