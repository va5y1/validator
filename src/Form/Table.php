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
      ];
      $form['add_table' . $k] = [
        '#type' => 'submit',
        '#value' => $this->t('Add Table'),
        '#submit' => ['::addTable'],
        // '#ajax' => [
        //          'callback' => '::addCallback',
        //          'event' => 'click',
        //        ],
      ];
    } //end for $k
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
    // $triggeringElement = $form_state->getTriggeringElement();
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
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}
