<?php

namespace App\Views;

class View
{
	public static function render(string $view, array $data = [], bool $useLayout = true): void
    {
        $viewFile = self::resolveViewPath($view);

        if (!file_exists($viewFile))
        {
            self::handleMissingFile($viewFile);
            return;
        }
        if ($useLayout)
        {
            Layout::header($data['title'] ?? 'Filmek');
        }
        extract($data);
        include $viewFile;
        if ($useLayout)
        {
            Layout::footer();
        }
    }
    private static function resolveViewPath(string $view): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . "$view.php";
    }
    private static function handleMissingFile(string $viewFile): void
    {
        error_log("View file not found: $viewFile");
        Display::message("View '$viewFile' not found.");
    }
}