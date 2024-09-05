<?php

namespace DDDCore\DTO;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JsonException;
use JsonSerializable;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Validator\LaravelValidator;
use ReflectionException;


/**
 * @class BaseDTO
 * @package DDDCore\DTO
 */
abstract class BaseDTO implements Jsonable, Arrayable, ArrayAccess, JsonSerializable
{
    use HasAttributes;

    /**
     * 默认接受参数
     * @var array|string[]
     */
    protected array $commonFields = ['id', 'keyword', 'page', 'limit'];

    /**
     * 接受参数
     * @var array
     */
    protected array $accessFields = [];

    /**
     * /**
     * 过滤验证接口
     * @var array|string[]
     */
    protected array $skipValidator = [];

    /**
     * @var LaravelValidator
     */
    protected LaravelValidator $validator;

    /**
     * 是否验证
     *
     * @var bool
     */
    private bool $isValidator = true;

    /** @var Request $request */
    private Request $request;


    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    abstract public function validator();

    /**
     * @throws ValidatorException
     */
    public function __construct()
    {
        $this->request    = app(Request::class);
        $this->attributes = [];
        $this->init();
    }

    /**
     * 接受数据并验证
     *
     * @return void
     * @throws ValidatorException
     */
    private function init(): void
    {
        $data = $this->request->all();

        $action = $this->getAction();

        $this->accessFields = $this->getAccessFields($action);

        $this->validator = is_string($this->validator()) ? app($this->validator()) : $this->validator();

        if (!empty($data)) {
            $enableData = collect($data)->filter(function ($val, $key) {
                return !empty($this->accessFields) && in_array($key, $this->accessFields, true);
            })->toArray();

            collect($enableData)->map(function ($val, $key) {
                $this->setAttribute($key, $val);
            });
        }

        //接口调整验证
        $skipValidator = $this->skipValidator;
        if (method_exists($this, 'skipValidator')) {
            $skipValidator = array_merge($this->skipValidator, $this->skipValidator());
        }
        if ($this->isValidator === true && (!empty($action) && in_array($action, $skipValidator, true) === false)) {
            $this->validator->with($this->getAttributes())->passesOrFail($action);
        }


    }

    /**
     * 获取允许执行字段
     *
     * @param  string  $action
     * @return array
     */
    public function getAccessFields(string $action = ''): array
    {
        if (!empty($action)) {
            $func = $action.'Fields';
            if (method_exists($this, $func)) {
                $this->accessFields = $this->$func();
            }

            $this->accessFields = array_merge($this->accessFields, $this->commonFields);
        }

        return $this->accessFields;
    }

    /**
     * 设置是否验证
     *
     * @param  bool  $isValidator
     * @return void
     */
    public function isValidator(bool $isValidator = true): void
    {
        $this->isValidator = $isValidator;
    }


    /**
     * 获取当前操作
     *
     * @return string
     */
    private function getAction(): string
    {
        return getActions($this->request)['controller'] ?? '';
    }



    /**
     * @return array
     * @throws ReflectionException
     */
//    private function getAccessFields(): array
//    {
//        $actions = getActions($this->request);
//        if (!empty($actions['controller'])) {
//            $class = new ReflectionClass($actions['controller']);
//            $properties = $class->getProperties();
//
//            if (!empty($properties)) {
//                foreach ($properties as $property) {
//                    $name = $property->getName();
//                    $type = $property->getType();
//
//                    if ($type instanceof ReflectionNamedType) {
//                        $typeName = $type->getName();
//                        echo "Property: $name, Type: $typeName\n";
//                    } else {
//                        echo "Property: $name, Type: unknown\n";
//                    }
//                }
//            }
//        }
//        return [];
//    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset(string $key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Unset an attribute on the model.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset(string $key)
    {
        $this->offsetUnset($key);
    }


    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return !is_null($this->getAttribute($offset));
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        // First we will check for the presence of a mutator for the set operation
        // which simply lets the developers tweak the attribute as it is set on
        // the model, such as "json_encoding" an listing of data for storage.
        if ($this->hasSetMutator($key)) {
            return $this->setMutatedAttributeValue($key, $value);
        }

        // If an attribute is listed as a "date", we'll convert it from a DateTime
        // instance into a form proper for storage on the database tables using
        // the connection grammar's date format. We will auto set the values.
        if ($value && $this->isDateAttribute($key)) {
            $value = $this->fromDateTime($value);
        }

        if ($this->isClassCastable($key)) {
            $this->setClassCastableAttribute($key, $value);

            return $this;
        }

        if (!is_null($value) && $this->isJsonCastable($key)) {
            $value = $this->castAttributeAsJson($key, $value);
        }

        // If this attribute contains a JSON ->, we'll set the proper value in the
        // attribute's underlying array. This takes care of properly nesting an
        // attribute in the array's value in the case of deeply nested items.
        if (Str::contains($key, '->')) {
            return $this->fillJsonAttribute($key, $value);
        }

        if (!is_null($value) && $this->isEncryptedCastable($key)) {
            $value = $this->castAttributeAsEncryptedString($key, $value);
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->getAttributes();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     * @throws JsonException
     */
    public function toJson($options = 0): string
    {
        return json_encode($this, JSON_THROW_ON_ERROR | $options);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }


    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    private function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Determine if the model uses timestamps.
     *
     * @return bool
     */
    private function usesTimestamps(): bool
    {
        return false;
    }


}
