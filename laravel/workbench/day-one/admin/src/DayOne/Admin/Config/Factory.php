<?php
/**
 * Factory.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 3/5/15
 * Time: 3:08 PM
 */

namespace DayOne\Admin\Config;

use Frozennode\Administrator\Config\Factory as ConfigFactory;

use Frozennode\Administrator\Config\Settings\Config as SettingsConfig;
use DayOne\Admin\Config\Model\Config as ModelConfig;

class Factory extends ConfigFactory {

	/**
	 * Gets an instance of the config
	 *
	 * @param array		$options
	 *
	 * @return \Frozennode\Administrator\Config\ConfigInterface
	 */
	public function getItemConfigObject(array $options)
	{
		if ($this->type === 'settings')
		{
			return new SettingsConfig($this->validator, $this->customValidator, $options);
		}
		else
		{
			return new ModelConfig($this->validator, $this->customValidator, $options);
		}
	}
}
