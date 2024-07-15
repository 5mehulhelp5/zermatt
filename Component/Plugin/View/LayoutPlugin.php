<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\Component\Plugin\View;

use Maddlen\Zermatt\Component\Render;
use Magento\Framework\View\Layout;

class LayoutPlugin
{
    public function __construct(
        protected readonly Render $componentRender
    )
    {
    }

    public function afterGetOutput(Layout $subject, string $result): string
    {
        return $this->componentRender->output($result);
    }
}
