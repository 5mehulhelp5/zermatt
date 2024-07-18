<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\Partial\Plugin\View;

use Maddlen\Zermatt\Partial\Render;
use Magento\Framework\View\Layout;

class LayoutPlugin
{
    public function __construct(
        protected readonly Render $partialRender
    )
    {
    }

    public function afterGetOutput(Layout $subject, string $result): string
    {
        return $this->partialRender->output($result);
    }
}
