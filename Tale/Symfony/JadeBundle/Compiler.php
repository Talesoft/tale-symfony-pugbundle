<?php

namespace Tale\Symfony\JadeBundle;

use Symfony\Component\Templating\Loader\LoaderInterface;
use Symfony\Component\Templating\TemplateNameParser;
use Tale\Jade\Compiler as JadeCompiler;
use Tale\Jade\Lexer;
use Tale\Jade\Parser;

class Compiler extends JadeCompiler
{

    private $nameParser;
    private $loader;

    public function __construct($options, TemplateNameParser $nameParser, LoaderInterface $loader, Parser $parser = null, Lexer $lexer = null)
    {

        $this->nameParser = $nameParser;
        $this->loader = $loader;

        parent::__construct($options, $parser, $lexer);
    }

    public function resolvePath($path, $extensions = null)
    {

        //Some older jade versions don't support : in paths, but Symfony uses them.
        //We add a special construct // for that to replace it optionally
        $path = str_replace('//', ':', $path);

        $ext = pathinfo($path, PATHINFO_EXTENSION);

        if (strpos($path, '@') !== false || strpos($path, ':') !== false) {

            //This is somewhat hacky, but it will be changed in Tale Jade soon.
            $template = $this->nameParser->parse($path);
            $path = (string)$this->loader->load($template);

            if ($path) //Path found, don't use original resolving
                return $path;
        }

        return parent::resolvePath($path, $extensions);
    }
}