<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

use Maddlen\Zermatt\App\Variable;
use Maddlen\Zermatt\Partial\Partial;
use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(ComponentRegistrar::MODULE, 'Maddlen_Zermatt', __DIR__);

if (!function_exists('zermatt_variable')) {
    function zermatt_variable(string $name, mixed $value): void
    {
        Variable::set($name, $value);
    }
}

if (!function_exists('variable')) {
    function variable(string $name, mixed $value): void
    {
        zermatt_variable($name, $value);
    }
}

if (!function_exists('zermatt_partial')) {
    function zermatt_partial(string $template, mixed $props = []): string
    {
        return Partial::add($template, $props);
    }
}

if (!function_exists('partial')) {
    function partial(string $template, mixed $props = []): string
    {
        return zermatt_partial($template, $props);
    }
}
