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
        foreach ($this->month as $v) {
          $form['table' . "$k"][$i][$v] = [
            '#type' => 'number',
            '#title' => $this
              ->t("$v"),
            '#title_display' => 'invisible',
          ];
        }
      }

      $form['table' . "$k"]['actions'] = [
        '#type' => 'actions',
      ];
      $form['table' . "$k"]['actions']['add_fields'] = [
        '#type' => 'submit',
        '#value' => $this->t('Add Year'),
        '#submit' => ['::addRow'],
//        '#ajax' => [
//          'callback' => '::addCallback',
//          'event' => 'click',
//        ],
      ];
      $form['table' . "$k"]['actions']['add_table'] = [
        '#type' => 'submit',
        '#value' => $this->t('Add Table'),
        '#submit' => ['::addTable'],
//        '#ajax' => [
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
      $form['table' . "$k"]['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
      ];
    }
    // $form['names_fieldset'] = [
    //      '#type' => 'fieldset',
    //      '#title' => $this->t('People coming to picnic'),
    //      '#prefix' => '<div id="names-fieldset-wrapper">',
    //      '#suffix' => '</div>',
    //    ];
    //
    //    for ($i = 0; $i < $this->number; $i++) {
    //    $form['names_fieldset']['name'][$i] = [
    //      '#type' => 'textfield',
    //        '#title' => $this->t('Name'),
    //      ];
    //    }
    //
    //    $form['names_fieldset']['actions'] = [
    //      '#type' => 'actions',
    //    ];
    //    $form['names_fieldset']['actions']['add_name'] = [
    //      '#type' => 'submit',
    //      '#value' => $this->t('Add one more'),
    //      '#submit' => ['::addOne'],
    //      '#ajax' => [
    //        'callback' => '::addmoreCallback',
    //        'wrapper' => 'names-fieldset-wrapper',
    //      ],
    //    ];
    return $form;
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

      };
    };
  }

}
