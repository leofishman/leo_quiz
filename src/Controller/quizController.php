<?php

namespace Drupal\leo_quiz\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Class quizController.
 */
class quizController extends ControllerBase {



  /**
   * Quiz.
   *
   * @return json
   *   Return quiz json.
   */
  public function quiz() {
    $response_array = [];
	$answers = [];
	$quiz_status = 0;
    $nid = \Drupal::request()->query->get('quiz') ?: 'default';
    $user_choise = \Drupal::request()->request->get('answer')?\Drupal::request()->request->get('answer'):'';
    is_numeric($nid)?$nid:0;
    $node = Node::load($nid);
    $response_array['Title'] = $node->getTitle();
    $uid = \Drupal::currentUser()->id();

    $userData = \Drupal::service('user.data');
  //    $userData->set('leo_quiz', $uid, $nid, 0);  //reset test by uncomment this
    $last_question = $userData->get('leo_quiz', $uid, $nid);
    $last_question = is_null($last_question)?0:$last_question;

	if(($node->getType() == 'leo_quiz') && ($node->status->value == 1)) {
	  if ($last_question < $node->get('field_quiz')->count()) {
	  // first question, first try
		if (empty($user_choise)) {
			$response_array['status'] = 'start';
			$response_array['message'] = 'lets start the test by answer this:';			
		} else { 
		// user send his choice
			if ($node->get('field_quiz')[$last_question]->entity->field_answer->value == $user_choise) {
			// user sent right choice
				$last_question++;
				if ($last_question == $node->get('field_quiz')->count()) {
				// quiz finished
                    $response_array['status'] = 'finish';
                    $response_array['message'] = '<h3 class"quiz_congrats">contratulations you finish the test!!</h3>';
				} else {
				// next question	
                    $response_array['status'] = 'right';
                    $response_array['message'] = 'you got it!!! now answer this:';  					
				}
			} else {
			// wrong choice, lets try again
                  $response_array['status'] = 'wrong';
                  $response_array['message'] = 'Wrong answer, please try again:';
                  $response_array['misc'] = array($last_question , $node->get('field_quiz')[$last_question]->entity->field_answer->value,$user_choise);
			}
		} 
		// fill array_response with question and answers
          if ($last_question == $node->get('field_quiz')->count()) {
              $response_array['question'] = [];
              $response_array['answers'] = [];
          } else {
              $question = $node->get('field_quiz')[$last_question]->entity->field_question->value;
              $answer = $node->get('field_quiz')[$last_question]->entity->field_answer->value;
              array_push($answers, $answer);
              $options = $node->get('field_quiz')[$last_question]->entity->field_option->getValue();
              for ($i = 0;$i < 3 ;$i++) {
                  $option = array_values($options[$i]);
                  array_push($answers, $option[0]);
              }
              shuffle($answers);
              $response_array['question'] = $question;
              $response_array['answers'] = $answers;
          }

          $userData->set('leo_quiz', $uid, $nid, $last_question);


	  } else { // last_question greater than total question for that quiz
          $response_array['status'] = 'finish';
          $response_array['message'] = '<h3 class"quiz_congrats">contratulations you finish the test!!</h3>';	  }
	}

	  // Build response with node serialized
	 $response = new JsonResponse([
		'quiz' => $response_array,
	  //  'node' => $node_encoded, //we dont need to send the node
	  ]);
	return $response;

  }

}
