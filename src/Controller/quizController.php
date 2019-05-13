<?php

namespace Drupal\leo_quiz\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class quizController.
 */
class quizController extends ControllerBase {

  /**
   * Quiz.
   *
   * @return string
   *   Return Hello string.
   */
  public function quiz() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: quiz')
    ];
  }

}
