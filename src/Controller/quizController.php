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
    $nid =\Drupal::request()->query->get('quiz') ?: 'default';
    
    is_numeric($nid)?$nid:0;
    $node = Node::load($nid);
    $title = $node->title->value;
    $type = $node->getType();
    $status = $node->status->value;
    if (($type == 'leo_quiz') && ($status == 1)) {
        $quiz = $node->get('field_quiz')->referencedEntities();
        // Encode node as json 
        $node_encoded = $this->serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
        $quiz_encoded = $this->serializer->serialize($quiz, 'json', ['plugin_id' => 'entity']);
        
        // Build response with node serialized
        $response = new JsonResponse([
          'quiz' => $quiz_encoded,
          'node' => $node_encoded,
        ]);
    
    return $response;     
    } else {
      return ;
    }

    
  }

}
