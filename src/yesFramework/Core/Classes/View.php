<?php

declare(strict_types=1);

namespace yesFramework\Core\Classes;

interface ViewInterface
{
        public static function input(string $type, string $name, array $options = []): string;
        public static function option(string $value1, ?string $value2 = NULL): string;
}

/*
 * View class
 */

class View implements ViewInterface
{

    public static function input(string $type, string $name, array $options = []): string
    {
        $attributes = [
            'type' => $type,
            'name' => $name,
        ];

        // Allowed standard attributes we want to extract from options
        $allowedKeys = ['id', 'class', 'placeholder', 'value', 'size', 'style', 'maxlength'];
        foreach ($allowedKeys as $key) {
            if (isset($options[$key])) {
                $attributes[$key] = (string)$options[$key];
            }
        }

        // Build attribute string safely escaping all values
        $attrParts = [];
        foreach ($attributes as $key => $val) {
            $attrParts[] = sprintf('%s="%s"', $key, htmlspecialchars($val, ENT_QUOTES, 'UTF-8'));
        }

        // Handle raw custom parameters like 'myparam' (e.g. checked, required, etc.)
        if (isset($options['myparam'])) {
            $attrParts[] = trim((string)$options['myparam']);
        }

        $result = '<input ' . implode(' ', $attrParts) . ' />';
        return preg_replace('/\s\s+/', ' ', $result);
    }

    /**
     * Generate option in HTML safely escaping output
     * @param string $value1
     * @param string $value2
     * @return string
     */
    public static function option(string $value1, ?string $value2 = NULL): string
    {
        if ($value2 === NULL) {
            $value2 = $value1;
        }
        return '<option value="' . htmlspecialchars($value1, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($value2, ENT_QUOTES, 'UTF-8') . '</option>';
    }
}
