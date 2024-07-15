<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\Setup;

use Maddlen\Zermatt\App\LockFile;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class Recurring implements InstallSchemaInterface
{
    public function __construct(
        protected readonly LockFile $lockFile
    )
    {
    }


    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $this->lockFile->dump();
    }
}
