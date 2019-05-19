<?php declare(strict_types=1);

namespace App\Core;

use Twig\Loader\FilesystemLoader;

final class View
{
    private $template;

    public function __construct(string $viewName)
    {
        $loader = new FilesystemLoader($this->templateDir());
        $twig = new \Twig\Environment($loader, [
            'cache' => $this->cacheDir(),
            'debug'
        ]);
        $this->template = $twig->load(sprintf('%s.html', $viewName));
    }

    /**
     * Render view
     *
     * @param array $dynamicVariables
     */
    public function render(array $dynamicVariables = []) : void
    {
        echo $this->template->render($dynamicVariables);
    }

    /**
     * Get templates cache dir
     *
     * @return string
     */
    public function cacheDir() : string
    {
        return sprintf('%s/%s', realpath('./'), 'bootstrap/cache/views');
    }

    /**
     * Get templates dir
     *
     * @return string
     */
    public function templateDir() : string
    {
        return sprintf('%s/Views', dirname(__DIR__, 1));
    }
}