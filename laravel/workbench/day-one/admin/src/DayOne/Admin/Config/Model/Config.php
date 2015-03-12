<?php
/**
 * Config.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 3/5/15
 * Time: 3:12 PM
 */

namespace DayOne\Admin\Config\Model;

use Frozennode\Administrator\Config\Model\Config as ModelConfig;

class Config extends ModelConfig {


	/**
	 * Gets the validation rules for this model
	 *
	 * @return array
	 */
	public function getModelValidationRules()
	{
		if(get_class($this->model) == 'User') {

			/** @var $this->model User */

			$this->model->addContext([
				'student-save',
				'password-save',
				'registration-save',
			]);

			if($this->model->isAdmin() || $this->model->isTutor()) {
				$this->model->addContext('tutor-save');
			}

			return $rules = $this->model->getValidationRules() ?: array();
		} else {
			return parent::getModelValidationRules();
		}
	}

}
