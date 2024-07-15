<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\App;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Theme\Model\ResourceModel\Theme\Collection;
use Magento\Theme\Model\Theme;

class Install
{

    private string $targetThemeCode;

    public function __construct(
        protected readonly Collection $themeList,
        protected readonly Filesystem $filesystem,
        protected readonly App        $app,
        protected readonly LockFile   $lockFile
    )
    {
    }

    public function install(string $targetThemeCode): Install
    {
        $this->setTargetThemeCode($targetThemeCode);
        $filesystem = new \Symfony\Component\Filesystem\Filesystem();
        $filesystem->mirror($this->app->sourceDir(), $this->getTargetThemeDir());

        $this->lockFile->dump();
        return $this;
    }

    public function setTargetThemeCode(string $targetThemeCode): void
    {
        $this->targetThemeCode = $targetThemeCode;
    }

    public function getTargetThemeDir(bool $absolute = true): string
    {
        $appDir = $this->filesystem->getDirectoryRead(DirectoryList::ROOT);
        $method = 'get' . ($absolute ? 'Absolute' : 'Relative') . 'Path';
        return $appDir->{$method}('app/design/' . $this->getTargetTheme()->getFullPath()) . '/web/zermatt';
    }

    public function getTargetTheme(): Theme
    {
        if ($this->targetThemeCode === '' || $this->targetThemeCode === '0') {
            throw new Exception('Please set a target theme code.');
        }

        return $this->themeList->getThemeByFullPath('frontend/' . $this->targetThemeCode);
    }


}
