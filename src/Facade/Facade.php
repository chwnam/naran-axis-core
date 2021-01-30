<?php

use Naran\Axis\Core\Layout\LayoutFactory;
use Naran\Axis\Core\Layout\LayoutInterface;
use Naran\Axis\Core\Layout\LayoutNotFoundException;
use Naran\Axis\Core\Layout\LayoutPool;

function axisStartPlugin(array $args = [])
{
    LayoutFactory::pluginLayout($args)->start();
}


function axisStartTheme(array $args = [])
{
    LayoutFactory::themeLayout($args)->start();
}


function axisGetLayout(string $name): ?LayoutInterface
{
    try {
        return LayoutPool::get($name);
    } catch (LayoutNotFoundException $e) {
        return null;
    }
}
