<?php
namespace App\Domains\CMS\StrategyCheck;

class FieldValidatorResolver
{
    public function resolve(string $type): FieldValidator
    {
        return match ($type) {
            'number' => new NumberFieldValidator(),
            'string' => new StringFieldValidator(),
            default => throw new \Exception("Unsupported field type: {$type}")
        };
    }
}
