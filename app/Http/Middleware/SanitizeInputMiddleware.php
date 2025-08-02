<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Input sanitization middleware for security
 */
class SanitizeInputMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Sanitize all input data
        $input = $request->all();
        $sanitizedInput = $this->sanitizeArray($input);

        // Replace request input with sanitized data
        $request->replace($sanitizedInput);

        return $next($request);
    }

    /**
     * Recursively sanitize array data
     *
     * @param array $data
     * @return array
     */
    private function sanitizeArray(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeArray($value);
            } elseif (is_string($value)) {
                $sanitized[$key] = $this->sanitizeString($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Sanitize string input
     *
     * @param string $input
     * @return string
     */
    private function sanitizeString(string $input): string
    {
        // Remove null bytes
        $input = str_replace(chr(0), '', $input);

        // Remove control characters except tab, newline, and carriage return
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);

        // Trim whitespace
        $input = trim($input);

        // Remove potential XSS attempts
        $input = $this->removeXssAttempts($input);

        // Remove SQL injection attempts
        $input = $this->removeSqlInjectionAttempts($input);

        return $input;
    }

    /**
     * Remove potential XSS attempts
     *
     * @param string $input
     * @return string
     */
    private function removeXssAttempts(string $input): string
    {
        // Common XSS patterns
        $xssPatterns = [
            '/javascript:/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/onclick\s*=/i',
            '/onmouseover\s*=/i',
            '/onfocus\s*=/i',
            '/onblur\s*=/i',
            '/onchange\s*=/i',
            '/onsubmit\s*=/i',
            '/<script[\s\S]*?<\/script>/i',
            '/<iframe[\s\S]*?<\/iframe>/i',
            '/<object[\s\S]*?<\/object>/i',
            '/<embed[\s\S]*?<\/embed>/i',
            '/<applet[\s\S]*?<\/applet>/i',
            '/<meta[\s\S]*?>/i',
            '/<link[\s\S]*?>/i',
            '/<style[\s\S]*?<\/style>/i',
        ];

        foreach ($xssPatterns as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }

        return $input;
    }

    /**
     * Remove potential SQL injection attempts
     *
     * @param string $input
     * @return string
     */
    private function removeSqlInjectionAttempts(string $input): string
    {
        // Common SQL injection patterns
        $sqlPatterns = [
            '/(\s|^)(union|select|insert|update|delete|drop|create|alter|exec|execute)(\s|$)/i',
            '/(\s|^)(or|and)(\s+\d+\s*=\s*\d+|\s+true|\s+false)/i',
            '/(\s|^)(script|javascript|vbscript):/i',
            '/(\s|^)(char|ascii|substring|length|user|database|version|@@)/i',
            '/(\s|^)(sleep|benchmark|waitfor|delay)/i',
            '/(\s|^)(load_file|into\s+outfile|into\s+dumpfile)/i',
            '/\/\*.*?\*\//s', // SQL comments
            '/--.*$/m', // SQL line comments
            '/;.*$/m', // Multiple statements
        ];

        // Don't modify if it looks like legitimate search terms
        if ($this->isLegitimateSearchTerm($input)) {
            return $input;
        }

        foreach ($sqlPatterns as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }

        return $input;
    }

    /**
     * Check if input looks like legitimate search term
     *
     * @param string $input
     * @return bool
     */
    private function isLegitimateSearchTerm(string $input): bool
    {
        // Allow normal search terms (letters, numbers, spaces, common punctuation)
        return preg_match('/^[\w\s\-.,!?]+$/u', $input) && strlen($input) < 100;
    }
}
