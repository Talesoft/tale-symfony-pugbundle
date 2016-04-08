<?php

namespace Tale\Symfony\JadeBundle;

use Symfony\Bundle\FrameworkBundle\Templating\TemplateNameParser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\Loader\LoaderInterface;
use Tale\Jade\Renderer;
use Tale\Symfony\JadeBundle\Renderer\Adapter\File;

class JadeEngine implements EngineInterface
{

    private $renderer;
    private $nameParser;
    private $loader;
    private $basicExtensions;

    public function __construct(array $options, ContainerInterface $container, TemplateNameParser $nameParser, LoaderInterface $loader)
    {

        $this->renderer = new Renderer([
            'adapter' => File::class,
            'cachePath' => $options['cache_dir']
        ], new Compiler([
            'pretty' => $options['pretty']
        ], $nameParser, $loader));

        $assetHelper = $container->has('templating.helper.assets')
                     ? $container->get('templating.helper.assets')
                     : ($container->has('assets.packages') ? $container->get('assets.packages') : null);

        $this->nameParser = $nameParser;
        $this->loader = $loader;
        $this->basicExtensions = [];

        if ($assetHelper)
            $this->basicExtensions['asset'] = function ($path, $packageName = null) use ($assetHelper) {

                return $assetHelper->getUrl($path, $packageName);
            };
    }

    public function render($name, array $parameters = array())
    {

        return $this->renderer->render($name, array_merge($this->basicExtensions, $parameters));
    }

    public function exists($name)
    {

        return $this->renderer->getCompiler()->resolvePath($name, null) !== null;
    }

    /**
     * Returns true if this class is able to render the given template.
     *
     * @param string $name A template name
     *
     * @return Boolean True if this class supports the given resource, false otherwise
     */
    public function supports($name)
    {

        $template = $this->nameParser->parse($name);
        return $template->get('engine') === 'jade';
    }
}