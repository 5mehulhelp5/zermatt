<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\Component;

class Component
{
    final public const PLACEHOLDER = 'zermatt_component:';

    protected static array $components = [];

    public static function add(string $template, mixed $props): string
    {
        $id = self::PLACEHOLDER . uniqid();
        static::$components[$id] = [
            'id' => $id,
            'template' => $template,
            'props' => $props
        ];

        echo $id; // Yes, echo the component id in the HTML
        return $id;
    }

    public static function all(): array
    {
        return static::$components;
    }
}
