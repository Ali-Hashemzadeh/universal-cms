<?php

namespace App\Services;

class FieldTypeService
{
    protected array $fieldTypes = [];

    /**
     * Register a new field type with its configuration.
     */
    public function register(string $name, array $config): void
    {
        $this->fieldTypes[$name] = $config;
    }

    /**
     * Get the configuration for a specific field type.
     */
    public function getType(string $name): ?array
    {
        return $this->fieldTypes[$name] ?? null;
    }

    /**
     * Get all registered field types.
     */
    public function getAllTypes(): array
    {
        return $this->fieldTypes;
    }

    /**
     * Get the default validation rules for a field type.
     */
    public function getValidationRules(string $typeName): array
    {
        $type = $this->getType($typeName);
        return $type['validation_rules'] ?? [];
    }

    /**
     * Serialize a value before saving it to the database.
     */
    public function serializeValue(string $typeName, $value)
    {
        $type = $this->getType($typeName);
        if (isset($type['serialize']) && is_callable($type['serialize'])) {
            return $type['serialize']($value);
        }
        return $value;
    }

    /**
     * Normalize a value after retrieving it from the database.
     */
    public function normalizeValue(string $typeName, $value)
    {
        $type = $this->getType($typeName);
        if (isset($type['normalize']) && is_callable($type['normalize'])) {
            return $type['normalize']($value);
        }
        return $value;
    }
}
