<?php

namespace Drupal\leo_quiz\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Class quizController.
 */
class quizController extends ControllerBase {

  protected $entityTypeManager;
  protected $serializer;
  
  /**
   * Construct implementation.
   * @param EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, SerializerInterface $serializer ) {
    $this->entityTypeManager = $entityTypeManager;
    $this->serializer = $serializer;
  }
  
  /**
   * Create implementation.
   * @param ContainerInterface $container
   * @return \Drupal\drupal_miseries\Rest\CustomRest
   */
  public static function create(ContainerInterface $container) {
    return new static (
      $container->get('entity_type.manager'),
      $container->get('serializer')
    );
  }


  /**
   * Quiz.
   *
   * @return string
   *   Return Hello string.
   */
  public function quiz() {
    $response_array = [];

    $nid =\Drupal::request()->query->get('quiz') ?: 'default';
    
    is_numeric($nid)?$nid:0;
    $node = Node::load($nid);
    $title = $node->title->value;
    if ($title){
      $tempstore = \Drupal::service('tempstore.private')->get('leo_quiz');
      $tempstore->get('leo_quiz_session', $quiz_status);
      $last_question = is_null($quiz_status[$nid]['question'])?0:$quiz_status[$nid]['question'];
      $type = $node->getType();
      $status = $node->status->value;
      $response_array['Title'] = $node->getTitle();
      if (($type == 'leo_quiz') && ($status == 1)) {
        foreach ($node->get('field_quiz') as $key => $para) {
          $question = [];
          $answers = [];
          if ($para->entity->getType() == 'leo_quiz') {  
            if ($key == $last_question) {
              $quiz = $para->entity;   
              $question = $quiz->field_question->value;
              $answer = $quiz->field_answer->value;
              array_push($answers, $answer);
              $options = $quiz->field_option->getValue();
              for ($i = 0;$i < 3 ;$i++) {
                $option = array_values($options[$i]);
                array_push($answers, $option[0]);
              }
              shuffle($answers);
              $response_array['question'] = $question;
              $response_array['answers'] = $answers;
            }
           
          }
        }
          $quiz_encoded = $this->serializer->serialize($response_array, 'json', ['plugin_id' => 'entity']);
          
          // Build response with node serialized
          $response = new JsonResponse([
            'quiz' => $response_array[$last_question],
          //  'node' => $node_encoded,
          ]);
      } else {
      
      }
    }  
      $response = new JsonResponse([
        'quiz' => $response_array,
      //  'node' => $node_encoded,
      ]);
     return $response;
  }

}
