<?php

namespace Drupal\leo_quiz\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a 'QuizBlock' block.
 *
 * @Block(
 *  id = "quiz_block",
 *  admin_label = @Translation("Quiz block"),
 * )
 */
class QuizBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
          ] + parent::defaultConfiguration();
  }


  

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $query = \Drupal::entityQuery('node');
    $query->condition('status', 1);
    $query->condition('type', 'leo_quiz')
    ->addTag('distinct');
    $nids = $query->execute();
    $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);
    foreach ($nodes as $nid => $node){
      $options[$nid] = $node->getTitle();
    }
    $form['quiz_to_show'] = [
      '#type' => 'select',
      '#title' => $this->t('Quiz to Show'),
      '#options' => $options,
      '#description' => $this->t('Select the Quiz to show in the block'),
      '#default_value' => $this->configuration['quiz_to_show'],
      '#weight' => '0',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['quiz_to_show'] = $form_state->getValue('quiz_to_show');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    //TODO atach javascript behavior to get the quiz api and draw the questions and submit button
   $build = [];
    $build['quiz_block_quiz_to_show']['#attached']['library'] = array('leo_quiz/leo_quiz_load_block_quiz');
    $build['quiz_block_quiz_to_show']['#markup'] = '<p class="load_block_quiz" ><span id="' . $this->configuration['quiz_to_show'] . '">
                                                    <div class="quiz_block" id="quiz_block_' . $this->configuration['quiz_to_show'] . '"> 
                                                    </div></span></p>';

    return $build;
  }

    /**
     * {@inheritdoc}
     */
    protected function blockAccess(AccountInterface $account) {
        return AccessResult::allowedIfHasPermission($account, 'view quiz');
    }

}
