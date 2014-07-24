<?php

App::uses('CroogoAppModel', 'Croogo.Model');

/**
 * Base Application model
 *
 * @package  Croogo
 * @link     http://www.croogo.org
 */
class AppModel extends CroogoAppModel {

    /**
     * Looks up the submitted value in the given model table and field name and
     * returns false if it doesn't exist. Default model name is the current model, and
     * default field name is the form's field name.
     *
     * @param array $field The name of the field to validate.
     * @param array $params 'model' specifies the model to look up, 'field' specifies the field name to use
     * @return bool True if submitted value exists in model.fieldname, false otherwise
     */
    function relatedExists($field, $params)
    {
        $fieldName = key($field);
        $fieldValue = current($field);

        $lookupModelName = isset($params['model'])? Inflector::camelize($params['model']) : $this->name;
        $lookupFieldName = isset($params['field'])? Inflector::camelize($params['field']) : $fieldName;
        $findFunc = 'findBy'.$lookupFieldName;

        if ($lookupModelName == $this->name)
        {
            $model = $this;
        }
        else
        {
            $name = $lookupModelName;
            if(isset($params['plugin'])) {
                $name = $params['plugin'] . '.' . $name;
            }
            App::import("Model", $name);
            $model = new $lookupModelName;
        }
        $result = $model->{$findFunc}($this->data[$this->name][$fieldName]);
        if(count($result) == 0) {
            return false;
        }
        return true;
    }
}
