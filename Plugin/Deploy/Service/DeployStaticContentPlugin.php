<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\Plugin\Deploy\Service;

use Maddlen\Zermatt\App\Build;
use Magento\Deploy\Service\DeployStaticContent;

class DeployStaticContentPlugin
{
    public function __construct(
        private Build $build
    )
    {
    }

    /**
     * @param DeployStaticContent $subject
     * @param array $options
     * @return array
     */
    public function beforeDeploy(DeployStaticContent $subject, array $options): array
    {
        $this->build->themes();
        return [$options];
    }
}
