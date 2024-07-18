<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\Partial;

class Partial
{
    final public const PLACEHOLDER = 'zermatt_partial:';

    protected static array $partials = [];

    public static function add(string $template, mixed $props): string
    {
        $id = self::PLACEHOLDER . uniqid();
        static::$partials[$id] = [
            'id' => $id,
            'template' => $template,
            'props' => $props
        ];

        echo $id; // Yes, echo the partial id in the HTML
        return $id;
    }

    public static function all(): array
    {
        return static::$partials;
    }
}
