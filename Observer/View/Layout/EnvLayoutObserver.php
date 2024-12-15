<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\Observer\View\Layout;

use Maddlen\Zermatt\App\App;
use Magento\Framework\App\State;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class EnvLayoutObserver implements ObserverInterface
{
    public function __construct(
        protected readonly State $appState,
        protected readonly App   $app
    )
    {
    }

    /**
     * Observer for layout_load_before
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if ($this->app->withZermatt()) {
            $isMageDevMode = $this->appState->getMode() === State::MODE_DEVELOPER;
            $handle = $isMageDevMode && $this->app->isViteDevMode() ? 'zermatt_developer_mode' : 'zermatt_production_mode';
            $observer->getLayout()->getUpdate()->addHandle($handle);
        }
    }
}
