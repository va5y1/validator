<?php

namespace Drupal\Validator\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EventSubmitForm.
 */
class FormClass extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'event_submit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#cache'] = ['max-age' => 0]; // just in case
    $form['#tree'] = TRUE;
    /* Presentation */
    $form['presentation'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Presentation'),
      '#weight' => 0,
    ];
    $form['presentation']['event_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title of the event'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => 0,
    ];

    $form['presentation']['photo'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Photo'),
      '#weight' => 0,
    ];

    $form['presentation']['event_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Event type'),
      '#options' => [
        'Type1' => $this->t('Type1'),
        'Type2' => $this->t('Type2'),
        'Type3' => $this->t('Type3'),
      ],
      '#size' => 1,
      '#weight' => 0,
    ];

    $form['presentation']['themes'] = [
      '#type' => 'radios',
      '#title' => $this->t('Themes'),
      '#options' => [
        'Theme1' => $this->t('Theme1'),
        'Theme2' => $this->t('Theme2'),
        'Theme3' => $this->t('Theme3'),
      ],
      '#weight' => 0,
    ];

    $form['presentation']['included_to_discovery'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Event to be included in the discovery book'),
      '#weight' => 0,
    ];

    $form['presentation']['discovery_category'] = [
      '#type' => 'radios',
      '#title' => $this->t('Category for discovery book'),
      '#options' => [
        'Category1' => $this->t('Category1'),
        'Category2' => $this->t('Category2'),
        'Category3' => $this->t('Category3'),
        'Category4' => $this->t('Category4'),
        'Category5' => $this->t('Category5'),
      ],
      '#weight' => 0,
    ];

    $form['presentation']['included_to_agenda'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Event to be included in the agenda Maison du Parc'),
      '#weight' => 0,
    ];

    $form['presentation']['description'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Description'),
      '#weight' => 0,
      '#format' => 'event_description',
    ];
    /* Dates and times */
    $date_time_nums = $form_state->get('date_time_nums');
    // We need a element to started, if not, set it.
    if ($date_time_nums === NULL) {
      $form_state->set('date_time_nums',
        [
          'dates' => [
            1 => [
              'times' => [1 => 'time'],
            ],
          ],
        ]
      );
      $date_time_nums = $form_state->get('date_time_nums');
    }

    $form['dates_and_times'] = [
      '#type' => 'fieldset',
      '#weight' => 0,
      '#title' => $this->t('Dates and times'),
      '#prefix' => '<div id="dates-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];

    // Loop needed items form.
    foreach ($date_time_nums['dates'] as $date_key => $date) {
      $form['dates_and_times'][$date_key]['next_date'] = [
        '#type' => 'date',
        '#title' => 'Date',
      ];
      $uniq_times_id = Html::getUniqueId('ajax-wrapper-' . $date_key);
      $form['dates_and_times'][$date_key]['times'] = [
        '#type' => 'container',
        '#prefix' => '<div id="' . $uniq_times_id . '">',
        '#suffix' => '</div>',
      ];
      // Loop sub items.
      foreach ($date['times'] as $time_key => $time) {
        $form['dates_and_times'][$date_key]['times']['actions'] = [
          '#type' => 'actions',
          'add_time' => [
            '#type' => 'submit',
            '#value' => t('Add one time'),
            '#submit' => ['::addOne'],
            '#name' => 'add_time_' . $date_key,
            '#ajax' => [
              'callback' => '::addMoreCallback',
              'wrapper' => $uniq_times_id,
            ],
          ],
        ];

        $form['dates_and_times'][$date_key]['times'][$time_key] = [
          'start_time' => [
            '#type' => 'select',
            '#title' => t('Start time'),
            '#options' => static::createTimeRange('00:00', '24:00', '15 min', '24'),
          ],
          'end_time' => [
            '#type' => 'select',
            '#title' => t('End time'),
            '#options' => static::createTimeRange('00:00', '24:00', '15 min', '24'),
          ],

        ];

        if ($time_key != 1) {
          $form['dates_and_times'][$date_key]['times'][$time_key]['actions'] = [
            '#type' => 'actions',
          ];
          $form['dates_and_times'][$date_key]['times'][$time_key]['actions']['remove_this_time'] = [
            '#type' => 'submit',
            '#name' => 'rm_times_' . $date_key . '_' . $time_key,
            '#value' => t('Remove ' . $time_key . ' time'),
            '#submit' => ['::removeCallbackOne'],
            '#ajax' => [
              'callback' => '::addMoreCallback',
              'wrapper' => $uniq_times_id,
            ],
          ];
        }
      }

      if ($date_key != 1) {
        $form['dates_and_times'][$date_key]['actions']['remove_this_date'] = [
          '#type' => 'submit',
          '#name' => 'rm_dates_' . $date_key,
          '#value' => t('Remove ' . $date_key . ' date'),
          '#submit' => ['::removeCallbackOne'],
          '#ajax' => [
            'callback' => '::addMoreCallback',
            'wrapper' => 'dates-fieldset-wrapper',
          ],
        ];
      }

    }

    $form['dates_and_times']['actions'] = [
      '#type' => 'actions',
    ];
    $form['dates_and_times']['actions']['add_date'] = [
      '#type' => 'submit',
      '#value' => t('Add one date'),
      '#submit' => ['::addOne'],
      '#name' => 'add_date',
      '#ajax' => [
        'callback' => '::addMoreCallback',
        'wrapper' => 'dates-fieldset-wrapper',
      ],
    ];
    /* Prices */
    $form['prices'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Prices'),
      '#weight' => 0,
    ];
    $form['prices']['free_event'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Free event'),
      '#weight' => 0,
    ];
    $form['prices']['rates_info'] = [
      '#markup' => $this->t('Several rates are possible'),
      '#weight' => 0,
    ];

    $prices_nums = $form_state->get('prices_nums');
    if ($prices_nums === NULL) {
      $form_state->set('prices_nums',
        [
          1 => 'price',
        ]
      );
      $prices_nums = $form_state->get('prices_nums');
    }
    $form['prices']['items'] = [
      '#type' => 'container',
      '#prefix' => '<div id="ajax-prices-items">',
      '#suffix' => '</div>',
      '#weight' => 0,
    ];
    foreach (array_keys($prices_nums) as $price_key) {
      $form['prices']['items'][$price_key]['type'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Type of audience'),
        '#maxlength' => 64,
        '#size' => 64,
        '#weight' => 0,
        '#placeholder' => 'Ex : Senior',
      ];
      $form['prices']['items'][$price_key]['rate'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Rate'),
        '#maxlength' => 64,
        '#size' => 64,
        '#weight' => 0,
        '#placeholder' => 'Ex : 10€',
      ];
      if ($price_key != 1) {
        $form['prices']['items'][$price_key]['actions'] = [
          '#type' => 'actions',
          'remove_this_date' => [
            '#type' => 'submit',
            '#name' => 'rm_price_' . $price_key,
            '#value' => t('Remove ' . $price_key . ' price'),
            '#submit' => ['::removeCallbackOne'],
            '#ajax' => [
              'callback' => '::addMoreCallback',
              'wrapper' => 'ajax-prices-items',
            ],
          ],
        ];
      }
    }
    // Action.
    $form['prices']['actions'] = [
      '#type' => 'actions',
    ];
    // Add.
    $form['prices']['actions']['add_price'] = [
      '#type' => 'submit',
      '#value' => t('Add one price'),
      '#submit' => ['::addOne'],
      '#name' => 'addPrice',
      '#ajax' => [
        'callback' => '::addMoreCallback',
        'wrapper' => 'ajax-prices-items',
      ],
    ];
    /* Location */
    $form['location'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Location'),
      '#weight' => 0,
    ];
    $form['location']['city'] = [
      '#type' => 'select',
      '#title' => $this->t('City'),
      '#options' => [
        'City1' => $this->t('City1'),
        'City2' => $this->t('City2'),
        'City3' => $this->t('City3'),
        'Category4' => $this->t('Category4'),
        'Category5' => $this->t('Category5'),
      ],
      '#size' => 1,
      '#weight' => 0,
    ];
    $form['location']['around'] = [
      '#type' => 'select',
      '#title' => $this->t('Around'),
      '#options' => [
        'Around1' => $this->t('Around1'),
        'Around2' => $this->t('Around2'),
        'Around3' => $this->t('Around3'),
        'Category4' => $this->t('Category4'),
        'Category5' => $this->t('Category5'),
      ],
      '#size' => 1,
      '#weight' => 0,
    ];
    $form['location']['meeting_place'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Meeting place'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => 0,
    ];
    $form['location']['location_positioning'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Location positioning on Google Maps'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => 0,
    ];

    /* Contact */
    $form['contact'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Contact'),
      '#weight' => 0,
    ];
    $form['contact']['organized_by'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Organized by'),
      '#weight' => 0,
    ];
    $form['contact']['animator_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name of the animator'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => 0,
    ];
    $form['contact']['name_of_the_animator_last'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name of the animator(last)'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => 0,
    ];
    $form['contact']['phone_contact'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone contact'),
      '#weight' => 0,
    ];
    $form['contact']['contact_mail'] = [
      '#type' => 'email',
      '#title' => $this->t('Contact mail'),
      '#weight' => 0,
    ];
    $form['contact']['photo'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Photo'),
      '#weight' => 0,
    ];
    $form['contact']['function'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Function'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => 0,
    ];
    $form['contact']['in_partnership_with'] = [
      '#type' => 'textfield',
      '#title' => $this->t('In partnership with'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => 0,
    ];

    /* Useful information */
    $form['useful_information'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Useful information'),
      '#weight' => 0,
    ];
    $form['useful_information']['booking'] = [
      '#type' => 'radios',
      '#title' => $this->t('Booking'),
      '#options' => [
        'Val1' => $this->t('Val1'),
        'Val2' => $this->t('Val2'),
        'Around3' => $this->t('Around3'),
        'Category4' => $this->t('Category4'),
        'Category5' => $this->t('Category5'),
      ],
      '#weight' => 0,
    ];
    $form['useful_information']['convenient'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Convenient'),
      '#options' => [
        '1' => $this->t('1'),
        '2' => $this->t('2'),
        '3' => $this->t('3'),
        '4' => $this->t('4'),
        '5' => $this->t('5'),
        '6' => $this->t('6'),
        '7' => $this->t('7'),
        '8' => $this->t('8'),
      ],
      '#weight' => 0,
    ];
    $form['useful_information']['further_information'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Further information'),
      '#weight' => 0,
    ];
    $form['useful_information']['brand_values_regional'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Brand Values Regional Natural Park'),
      '#weight' => 0,
    ];

    $form['buttons'] = [
      '#type' => 'fieldset',
      '#weight' => 0,
    ];
    $form['buttons']['validate'] = [
      '#type' => 'button',
      '#value' => $this->t('Validate'),
      '#ajax' => [
        'callback' => '::ajaxValidate',
      ],
    ];
    $form['buttons']['save_and_exit'] = [
      '#type' => 'button',
      '#value' => $this->t('Save and exit'),
      '#ajax' => [
        'callback' => '::ajaxSaveAndExit',
      ],
    ];
    $form['buttons']['cancel'] = [
      '#type' => 'button',
      '#value' => $this->t('Cancel'),
      '#ajax' => [
        'callback' => '::ajaxCancel',
      ],
    ];

    $form_state->setCached(FALSE);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Do not need.
    // This is Ajax form.
  }

  /**
   * Ajax callback for 'Validate' button.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function ajaxValidate(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $response->addCommand(
      new HtmlCommand('.event-submit-form', '<h1>ajaxValidate() submitted!</h1>')
    );
    return $response;
  }

  /**
   * Ajax callback for 'Save and exit' button.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function ajaxSaveAndExit(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $response->addCommand(
      new HtmlCommand('.event-submit-form', '<h1>ajaxSaveAndExit() submitted!</h1>')
    );
    return $response;
  }

  /**
   * Ajax callback for 'Cancel' button.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function ajaxCancel(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $response->addCommand(
      new HtmlCommand('.event-submit-form', '<h1>ajaxCancel() submitted!</h1>')
    );
    return $response;
  }

  /**
   * Callback for both ajax-enabled buttons.
   *
   * Selects and returns the fieldset with the names in it.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array|mixed
   */
  public function addMoreCallback(array &$form, FormStateInterface $form_state) {
    $trigger = $form_state->getTriggeringElement();
    $name = substr($trigger["#name"], 0, 8);
    switch ($name) {
      case 'add_date':
      case 'rm_dates':
        return $form['dates_and_times'];

        break;
      case 'add_time':
      case 'rm_times':
        $date_delta = $trigger["#array_parents"][1];
        return $form["dates_and_times"][$date_delta]["times"];

        break;
      case 'rm_price':
      case 'addPrice':
        return $form['prices']['items'];

        break;
    }
    return ['#markup' => '<b>Error!</b>'];
  }

  /**
   * Submit handler for the "add-one-more" buttons.
   *
   * Increments the max counter and causes a rebuild.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $trigger = $form_state->getTriggeringElement();
    $name = substr($trigger["#name"], 0, 8);
    switch ($name) {
      case 'add_date':
        $deltas = $form_state->get('date_time_nums');
        $deltas['dates'][max(array_keys($deltas['dates'])) + 1] = ['times' => [1 => 'time']];
        $form_state->set('date_time_nums', $deltas);
        break;

      case 'add_time':
        $deltas = $form_state->get('date_time_nums');
        $dates_count = $trigger["#parents"][1];
        $deltas["dates"][$dates_count]["times"][] = 'time';
        $form_state->set('date_time_nums', $deltas);
        break;

      case 'addPrice':
        $deltas = $form_state->get('prices_nums');
        $deltas[max(array_keys($deltas)) + 1] = 'price';
        $form_state->set('prices_nums', $deltas);
        break;
    }

    $form_state->setRebuild();
  }

  /**
   * Submit handler for the "remove this item" buttons.
   *
   * Search the triggering element and unset it
   * Clean unset new array and rebuild it.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function removeCallbackOne(array &$form, FormStateInterface $form_state) {
    $trigger = $form_state->getTriggeringElement();
    $name = substr($trigger["#name"], 0, 8);
    switch ($name) {
      case 'rm_dates':
        $deltas = $form_state->get('date_time_nums');
        $delta_remove = $trigger["#array_parents"][1];
        unset($deltas["dates"][$delta_remove]);
        $form_state->set('date_time_nums', $deltas);
        break;

      case 'rm_times':
        $deltas = $form_state->get('date_time_nums');
        $date_delta = $trigger["#array_parents"][1];
        $delta_remove = $trigger["#array_parents"][3];
        unset($deltas["dates"][$date_delta]['times'][$delta_remove]);
        $form_state->set('date_time_nums', $deltas);
        break;

      case 'rm_price':
        $deltas = $form_state->get('prices_nums');
        $delta_remove = $trigger["#array_parents"][2];
        unset($deltas[$delta_remove]);
        $form_state->set('prices_nums', $deltas);
        break;
    }

    $form_state->setRebuild();
  }

  /**
   * Provide times option list.
   *
   * @param $start
   * @param $end
   * @param string $interval
   * @param string $format
   *
   * @return array
   */
  public static function createTimeRange($start, $end, $interval = '30 mins', $format = '12') {
    $startTime = strtotime($start);
    $endTime = strtotime($end);
    $returnTimeFormat = ($format == '12') ? 'g:i A' : 'G:i';

    $current = time();
    $addTime = strtotime('+' . $interval, $current);
    $diff = $addTime - $current;

    $times = [];
    while ($startTime < $endTime) {
      $times[] = date($returnTimeFormat, $startTime);
      $startTime += $diff;
    }
    $times[] = date($returnTimeFormat, $startTime);
    return $times;
  }

}
