<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\App;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Container
{
    public static function get(string $class): ArgumentInterface
    {
        return ObjectManager::getInstance()->get($class);
    }
}
