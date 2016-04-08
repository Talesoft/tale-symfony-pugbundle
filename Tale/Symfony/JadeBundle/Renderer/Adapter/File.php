<?php

namespace Tale\Symfony\JadeBundle\Renderer\Adapter;

use Tale\Jade\Renderer\Adapter\File as FileAdapter;

class File extends FileAdapter
{
    public function render($path, array $args = null)
    {

        $compilerOptions = $this->getRenderer()->getCompiler()->getOptions();
        $exts = $compilerOptions['extensions'];

        $outputPath = $path;
        foreach ($exts as $ext) {

            if (substr($path, -strlen($ext)) === $ext) {

                $outputPath = substr($path, 0, -strlen($ext));
                break;
            }
        }

        $outputPath = str_replace(['@', ':'], '-', $outputPath);
        $outputPath = rtrim($this->getOption('path'), '/\\') . '/' . ltrim($outputPath . $this->getOption('extension'), '/\\');


        $render = function ($__path, $__args) {

            ob_start();
            extract($__args);
            include($__path);

            return ob_get_clean();
        };

        if (!file_exists($outputPath) || time() - filemtime($outputPath) >= $this->getOption('lifeTime')) {

            $dir = dirname($outputPath);

            if (!is_dir($dir)) {

                @mkdir($dir, 0775, true);

                if (!is_dir($dir))
                    throw new \RuntimeException(
                        "Failed to create directory $dir"
                    );
            }

            file_put_contents($outputPath, $this->getRenderer()->compileFile($path));
        }

        return $render($outputPath, $args ? $args : []);
    }
}