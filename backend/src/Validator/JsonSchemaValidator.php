<?php

namespace App\Validator;

use Opis\JsonSchema\Validator;

class JsonSchemaValidator
{
    public function __construct(private string $projectDir)
    {
    }

    /** Retourne la liste des erreurs (vide si les données sont valides). */
    public function validate(array $data, string $schemaName): array
    {
        $schema = file_get_contents($this->projectDir . '/src/Validator/schemas/' . $schemaName . '.schema.json');
        $validator = new Validator();
        $result = $validator->validate(json_decode(json_encode($data)), $schema);

        if ($result->isValid()) {
            return [];
        }
        $error = $result->error();

        return [$error->message() . ' (' . implode('/', $error->data()->path()) . ')'];
    }
}