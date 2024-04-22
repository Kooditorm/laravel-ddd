<?php

namespace DDDCore\Validators;

use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Prettus\Validator\LaravelValidator;

/**
 * @class Validators
 * @package DDDCore\Validators
 */
class Validators extends LaravelValidator
{
    /**
     * 验证字段
     *
     * @var array
     */
    protected array $fields = [];

    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [];


    /**
     * Validation Custom Messages
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Validation Custom Attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Pass the data and the rules to the validator
     *
     * @param  string  $action
     * @return bool
     */
    public function passes($action = null): bool
    {
        $isValidator = true;
        $rules       = $this->getRules($action);

        if (array_key_exists($action, $this->rules) === false) {
            $isValidator = false;
        }

        $messages   = $this->getMessages();
        $attributes = $this->getAttributes();
        $validator  = $this->validator->make($this->data, $rules, $messages, $attributes);

        if ($isValidator && $validator->fails()) {
            $message = $validator->messages();
            if ($message !== null) {
                $messages = $message->getMessages();
                $tranMsgs = $messages;
                foreach ($messages as $k => $v) {
                    if (Arr::has($this->fields, $k)) {
                        $field   = $this->fields[$k] ?? '';
                        $comment = $field['comment'] ?? '';
                        if (!empty($comment) && !empty($v)) {
                            $transVal = $v;
                            foreach ($v as $ik => $iv) {
                                if (Str::contains($iv, str_replace('_', ' ', $k))) {
                                    $transVal[$ik] = str_replace(str_replace('_', ' ', $k), $comment, $iv);
                                }
                            }
                            $tranMsgs[$k] = $transVal;
                        }
                    }
                }
                $this->errors = new MessageBag($tranMsgs);
            }
            return false;
        }
        return true;
    }
}
