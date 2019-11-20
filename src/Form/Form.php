<?php

namespace Drupal\validator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a validator form.
 */
class Form extends FormBase {

  /**
   * @var array12month
   */
  public $month = [
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
   * @var int
   */
  public $num = 3;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'validator_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#tree'] = TRUE;
    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('This forms doing very strange things'),
    ];
    foreach ($this->month as $v) {
      $form[$v] = [
        '#type' => 'number',
        '#description' => $this->t('Number of month'),
      ];
    }
    $form['Q1'] = [
      '#type' => 'number',
      '#description' => $this->t('квартал 1'),
    ];
    $form['Q2'] = [
      '#type' => 'number',
      '#description' => $this->t('квартал 2'),
    ];
    $form['Q3'] = [
      '#type' => 'number',
      '#description' => $this->t('квартал 3'),
    ];
    $form['Ytd'] = [
      '#type' => 'number',
      '#description' => $this->t('Річний звіт'),
    ];
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    $form['actions']['add'] = [
      '#type' => 'submit',
      '#value' => $this->t('add more'),
      '#submit' => ['::add_item'],
    ];
    $form['#theme'] = 'validator_form';

    for ($i = 1; $i <= $this->num; $i++) {
      $form["num" . "$i"] = $form;
    }

    return $form;
  }

  /**
   *
   */
  public function add_item(array &$form, FormStateInterface $form_state) {
    $this->num+=1;
    $form_state->setRebuild();
    return $form;
  }

  /**
   *
   */
  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    $this->num++;
    $form_state->setRebuild();
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm();
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    $form_state->setRedirect('/valid');
  }

}
