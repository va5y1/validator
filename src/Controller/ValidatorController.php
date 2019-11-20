<?php

namespace Drupal\validator\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for validator routes.
 */
class ValidatorController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];
    return $build;
  }

}
