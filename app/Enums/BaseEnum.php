<?php

namespace App\Enums;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;

abstract class BaseEnum implements CastsAttributes
{
    protected mixed $value = null;

    protected string $enumClass;

    public function __construct()
    {
        $this->enumClass = static::class;
    }

    public function get($model, string $key, $value, array $attributes): BaseEnum
    {
        $class = new $this->enumClass($value);
        $class->value = $value;
        $this->value = get_class_vars($this->enumClass);

        return $class;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value instanceof $this->enumClass ? $value->value : $value;
    }

    protected function getDom(string $label, string $color): string
    {
        $color = 'badge bg-label-'.$color;

        return '<span class="'.$color.'">'.$label.'</span>';
    }

    public static function cases(): array
    {
        $reflection = new \ReflectionClass(static::class);

        return $reflection->getConstants();
    }

    public static function labels(): array
    {
        $data = [];
        $class = new static;

        foreach (static::cases() as $value) {
            $class->value = $value;
            $data[$value] = $class->getLabel();
        }

        return $data;
    }

    protected function getLabel(): string
    {
        return Str::pascal(Str::lower($this->value));
    }

    public function getColor(): string
    {
        return 'primary';
    }

    public function toHtml(): string
    {
        return $this->getDom($this->getLabel(), $this->getColor());
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public static function values(): array
    {
        return array_values(static::cases());
    }
}
