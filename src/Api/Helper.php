<?php

namespace ChrisPenny\WebPageTest\Api;

use Opis\JsonSchema;

/**
 * Class Helper
 *
 * @package ChrisPenny\WebPageTest\Api\Validation
 */
class Helper
{
    /**
     * @param JsonSchema\ValidationResult $result
     * @return array
     */
    public static function getValidationResultsAsArray(JsonSchema\ValidationResult $result): array
    {
        if ($result->isValid()) {
            return [];
        }

        $errors = [];

        foreach ($result->getErrors() as $validationError) {
            $errors[] = [
                'field' => $validationError->dataPointer(),
                'errors' => $validationError->keywordArgs(),
            ];
        }

        return $errors;
    }

    /**
     * @param JsonSchema\ValidationResult $result
     * @return string
     */
    public static function getValidationResultsAsJson(JsonSchema\ValidationResult $result): string
    {
        return json_encode(static::getValidationResultsAsArray($result), JSON_PRETTY_PRINT);
    }
}
