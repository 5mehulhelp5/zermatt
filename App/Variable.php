<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\App;

use InvalidArgumentException;
use Magento\Framework\Data\Collection;
use Magento\Framework\DataObject;
use Magento\Framework\Escaper;

class Variable
{
    private static array $variables = [];

    public static function set(string $name, mixed $value)
    {
        if (!(($value instanceof DataObject || $value instanceof Collection) || !is_object($value))) {
            throw new InvalidArgumentException('Invalid variable value');
        }

        if ($value instanceof DataObject || $value instanceof Collection) {
            $value = (new Escaper())->escapeJs(json_encode($value->toArray(), JSON_THROW_ON_ERROR));
        }

        static::$variables[$name] = $value;
    }

    public static function all(): array
    {
        return static::$variables;
    }

    public function get(string $name): mixed
    {
        return static::$variables[$name] ?? null;
    }
}
