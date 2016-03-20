<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

  /**
  * Makes a message that informs client about request success
  * @param errors array of errors
  * @param success_message message to send if no errors
  * @return array message
  */
  public function makeMessage($errors, $success_message) {
    $m = array(
      'type' => 'success',
      'message' => $success_message
    );

    if (count($errors) > 0) {
      $e = '';
      foreach ($errors as $error) {
        if (is_array($error)) {
          $e .= $error[0] . "\n";
        }
        else {
          $e .= $error . "\n";
        }
      }
      $m['type'] = 'error';
      $m['message'] = $e;
    }

    return $m;
  }
}
