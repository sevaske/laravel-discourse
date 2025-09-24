<?php

if (! function_exists('env_array')) {
    /**
     * Get an environment variable and parse it as an array.
     */
    function env_array(string $key, string|array|null $default = null, string $delimiter = ','): array
    {
        $value = env($key, $default);

        if ($value === null) {
            if (is_array($default)) {
                return $default;
            }

            if ($default === null) {
                return [];
            }

            $value = $default;
        }

        return array_map('trim', explode($delimiter, $value));
    }
}
