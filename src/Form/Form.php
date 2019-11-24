<?php

namespace Drupal\Validator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *
 */
class Form extends FormBase {

  /**
   *
   */
  public function getFormId() {
    return 'form_ex';
  }

  /**
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state, array $profile = NULL) {

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Submit'),
    ];

    // Table.
    $form['myTable'] = [
      '#type' => 'table',
      '#header' => [t('COL 1'), t('COL 2'), t('COL 3')],
      '#prefix' => '<div id="my-table-wrapper">',
      '#suffix' => '</div>',
    ];
    // Build the table rows and columns.
    for ($cpt = 0; $cpt <= 5; $cpt++) {
      // Table row.
      $form['myTable'][$cpt] = $this->getTableLine($cpt);
    }

    // Build the extra lines.
    $triggeringElement = $form_state->getTriggeringElement();
    $clickCounter = 0;
    // If a click occurs.
    for ($i = 2; $i < 3; $i++) {
      if ($triggeringElement and $triggeringElement['#attributes']['id'] == 'add-row' . $i) {
        // Click counter is incremented
        // $formstate and $form element are updated.
        $clickCounter = $form_state->getValue('click_counter');
        $clickCounter++;
        $form_state->setValue('click_counter', $clickCounter);
        $form['click_counter'] = [
          '#type' => 'hidden',
          '#default_value' => 0,
          '#value' => $clickCounter,
        ];
      }
      else {
        $form['click_counter'] = ['#type' => 'hidden', '#default_value' => 0];
      }
    }
    // Build the extra table rows and columns.
    for ($k = 0; $k < $clickCounter; $k++) {
      $form['myTable'][6 + $k] = $this->getTableLine(6 + $k);
    }
    for ($i = 0; $i < 3; $i++) {
      $form['addRow' . $i] = [
        '#type' => 'button',
        '#value' => t('Add a row'),
        '#ajax' => [
          'callback' => '::ajaxAddRow',
          'event' => 'click',
          'wrapper' => 'my-table-wrapper',
        ],
        '#attributes' => [
          'id' => 'add-row' . $i,
        ],
      ];
    }
    return $form;

  }

  /**
   *
   */
  public function getTableLine($key) {
    $line = [];

    $line['col_1'] = [
      '#type' => 'textfield',
      '#default_value' => 'col 1 - row ' . $key,
    ];
    $line['col_2'] = [
      '#type' => 'textfield',
      '#default_value' => 'col 2 - row ' . $key,
    ];
    $line['col_3'] = [
      '#type' => 'textfield',
      '#default_value' => 'col 3 - row ' . $key,
    ];
    return $line;
  }

  /**
   *
   */
  public function ajaxAddRow(array &$form, FormStateInterface $form_state) {
    return $form;
  }

  /**
   *
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}
