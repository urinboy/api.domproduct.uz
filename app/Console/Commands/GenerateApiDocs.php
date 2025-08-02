<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class GenerateApiDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:docs {--output=docs/api.md}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate API documentation from routes and comments';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating API documentation...');

        $routes = $this->getApiRoutes();
        $documentation = $this->generateDocumentation($routes);

        $outputPath = $this->option('output');
        file_put_contents($outputPath, $documentation);

        $this->info("API documentation generated: {$outputPath}");
        return 0;
    }

    /**
     * Get all API routes
     *
     * @return array
     */
    private function getApiRoutes(): array
    {
        $apiRoutes = [];

        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();

            // Only include API routes
            if (Str::startsWith($uri, 'api/')) {
                $apiRoutes[] = [
                    'method' => implode('|', $route->methods()),
                    'uri' => $uri,
                    'name' => $route->getName(),
                    'action' => $route->getActionName(),
                    'middleware' => $route->gatherMiddleware(),
                ];
            }
        }

        return $apiRoutes;
    }

    /**
     * Generate markdown documentation
     *
     * @param array $routes
     * @return string
     */
    private function generateDocumentation(array $routes): string
    {
        $doc = "# API Documentation\n\n";
        $doc .= "Generated on: " . now()->format('Y-m-d H:i:s') . "\n\n";
        $doc .= "## Table of Contents\n\n";

        // Group routes by controller
        $groupedRoutes = $this->groupRoutesByController($routes);

        // Generate table of contents
        foreach ($groupedRoutes as $controller => $controllerRoutes) {
            $doc .= "- [{$controller}](#{$this->anchorize($controller)})\n";
        }

        $doc .= "\n---\n\n";

        // Generate documentation for each controller
        foreach ($groupedRoutes as $controller => $controllerRoutes) {
            $doc .= "## {$controller}\n\n";

            foreach ($controllerRoutes as $route) {
                $doc .= $this->generateRouteDocumentation($route);
            }

            $doc .= "\n---\n\n";
        }

        return $doc;
    }

    /**
     * Group routes by controller
     *
     * @param array $routes
     * @return array
     */
    private function groupRoutesByController(array $routes): array
    {
        $grouped = [];

        foreach ($routes as $route) {
            $controller = $this->extractControllerName($route['action']);
            $grouped[$controller][] = $route;
        }

        return $grouped;
    }

    /**
     * Extract controller name from action
     *
     * @param string $action
     * @return string
     */
    private function extractControllerName(string $action): string
    {
        if (Str::contains($action, '@')) {
            $parts = explode('@', $action);
            $controller = class_basename($parts[0]);
            return Str::replace('Controller', '', $controller);
        }

        return 'Unknown';
    }

    /**
     * Generate documentation for a single route
     *
     * @param array $route
     * @return string
     */
    private function generateRouteDocumentation(array $route): string
    {
        $doc = "### {$route['method']} /{$route['uri']}\n\n";

        if ($route['name']) {
            $doc .= "**Route Name:** `{$route['name']}`\n\n";
        }

        $doc .= "**Middleware:** " . implode(', ', $route['middleware']) . "\n\n";

        // Add example request
        $doc .= "**Example Request:**\n";
        $doc .= "```bash\n";
        $method = strtoupper(explode('|', $route['method'])[0]);
        $doc .= "curl -X {$method} \\\n";
        $doc .= "  'http://localhost:8001/{$route['uri']}' \\\n";
        $doc .= "  -H 'Accept: application/json' \\\n";
        $doc .= "  -H 'Content-Type: application/json'\n";
        $doc .= "```\n\n";

        // Add example response
        $doc .= "**Example Response:**\n";
        $doc .= "```json\n";
        $doc .= "{\n";
        $doc .= "  \"data\": [],\n";
        $doc .= "  \"message\": \"Success\"\n";
        $doc .= "}\n";
        $doc .= "```\n\n";

        return $doc;
    }

    /**
     * Convert string to anchor format
     *
     * @param string $text
     * @return string
     */
    private function anchorize(string $text): string
    {
        return Str::slug(Str::lower($text));
    }
}
