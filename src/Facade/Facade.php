<?php

function axisStartPlugin(array $args = [])
{
    \Naran\Axis\Core\Layout\LayoutFactory::pluginLayout($args)->start();
}


function axisStartTheme(array $args = [])
{
    \Naran\Axis\Core\Layout\LayoutFactory::themeLayout($args)->start();
}


function axisGetLayout(string $name): ?\Naran\Axis\Core\Layout\LayoutInterface
{
    try {
        return \Naran\Axis\Core\Layout\LayoutPool::get($name);
    } catch (\Naran\Axis\Core\Layout\LayoutNotFoundException $e) {
        return null;
    }
}
