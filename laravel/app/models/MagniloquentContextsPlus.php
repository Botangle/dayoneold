<?php

use Magniloquent\Magniloquent\Magniloquent;


class MagniloquentContextsPlus extends Magniloquent
{
    const DEFAULT_KEY = 'save';

    /**
     * Store any contexts we are validating within.
     *
     * Idea shamelessly lifed from https://github.com/crhayes/laravel-extended-validator
     *
     * @var array
     */
    protected $contexts = array();

    /**
     * Add a validation context.
     *
     * Idea for contexts (like Yii scenarios) gratefully lifted (stolen? ;-) from https://github.com/crhayes/laravel-extended-validator
     *
     * @param string|array $context
     * @return $this
     */
    public function addContext($context)
    {
        $context = is_array($context) ? $context : array($context);

        $this->contexts = array_merge($this->contexts, $context);

        return $this;
    }

    /**
     * Remove a validation context.
     *
     * @param string|array $context
     * @return $this
     */
    public function removeContext($context)
    {
        if ($key = array_search($context, $this->contexts) !== false){
            unset($this->contexts[$key]);
        }
    }

    /**
     * Check if the current validation has a context.
     *
     * @return boolean
     */
    private function hasContext()
    {
        return (count($this->contexts) OR array_get($this->rules, static::DEFAULT_KEY));
    }

    /**
     * Merges saving validation rules in with create and update rules
     * to allow rules to differ on create and update.
     *
     * @return array
     */
    protected function mergeRules()
    {
        // fall back to our basic Magniloquent validation setup if we can
        if(!$this->hasContext()) {
            parent::mergeRules();
        } else {

            // but if we've got contexts, then we'll need to handle things using the
            // Laravel Extended Validator setup we have below with some modifications

            $rules = static::$rules;
            $output = array();

            // adjusted these two next lines to use our save rules as a base
            // but to apply the contexts on top of what we had
            // The Magniloquent setup only provided two basic contexts (update and create)
            // while the extended validator system didn't allow a base rule set that could be modified
            if ($this->exists)
                $merged = array_merge_recursive($rules[static::DEFAULT_KEY], $this->getRulesInContext());
            else
                $merged = array_merge_recursive($rules[static::DEFAULT_KEY], $this->getRulesInContext());

            foreach ($merged as $field => $rules)
            {
                if (is_array($rules))
                    $output[$field] = implode("|", $rules);
                else
                    $output[$field] = $rules;
            }

            $this->mergedRules = $output;
        }
    }

    /**
     * Make our validation rules accessible so we can pass them into the Former system
     * and have all our fields properly display required rules and be validated using client-side
     * stuff
     *
     * @return array
     */
    public function getValidationRules()
    {
        $this->mergeRules();
        return $this->mergedRules;
    }

    public function validate()
    {
        $return = parent::validate();

        if(!$return) {
            return $return;
        } else {

            // add in our own ability to call additional conditional rules
            // this is a bit ugly (it's a meld of Magniloquent's nice system and the conditional rules setup
            // but it works better than not having the ability to do one-off calls
            $validation = Validator::make($this->attributes, array(), $this->customMessages);
            $this->addConditionalRules($validation);

            // there were no conditional rules added
            if(count($validation->getRules()) == 0) {
                return true;
            }

            if ($validation->passes()) {
                return true;
            }

            $this->validationErrors = $validation->messages();

            return false;
        }
    }

    /**
     * Stub method that can be extended by child classes.
     * Passes a validator object and allows for adding complex conditional validations.
     *
     * See sample here: https://github.com/crhayes/laravel-extended-validator#adding-custom-complex-validation
     * Should be able to use contexts as well
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    protected function addConditionalRules($validator) {}

    /**
     * Get the validaton rules within the context of the current validation.
     *
     * Pulled from Laraval Extended Validator setup
     * https://github.com/crhayes/laravel-extended-validator/blob/0.6/src/Crhayes/Validation/ContextualValidator.php#L233-256
     *
     * @return array
     */
    private function getRulesInContext()
    {
        if ( ! $this->hasContext())	return static::$rules;

        $rulesInContext = array();

        foreach ($this->contexts as $context)
        {
            if ( ! array_get(static::$rules, $context))
            {
                throw new \Exception(
                    sprintf(
                        "'%s' does not contain the validation context '%s'",
                        get_called_class(),
                        $context
                    )
                );
            }

            // turns out we needed to do an array_merge recursive for what we were trying to do here
            $rulesInContext = array_merge_recursive($rulesInContext, static::$rules[$context]);
        }

        return $rulesInContext;
    }
}
