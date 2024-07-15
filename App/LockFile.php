<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\App;

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Module\ModuleList;
use Magento\Framework\View\Design\Theme\ThemePackage;
use Magento\Framework\View\Design\Theme\ThemePackageList;

class LockFile
{
    private array $modulesConfig = [];

    private array $themesConfig = [];

    private array $mergedThemes = [];

    private array $themePaths = [];

    public function __construct(
        protected readonly ModuleList         $moduleList,
        protected readonly ComponentRegistrar $componentRegistrar,
        protected readonly ThemePackageList   $themePackageList
    )
    {
    }

    /**
     * Much like the composer.lock or package-lock.json files,
     * Zermatt's lock file (zermatt-lock.json) contains
     * configuration for the current install.
     *
     * It must be dumped (bin/magento zermatt:lock:dump) after
     * any change in any zermatt.json file.
     */
    public function dump(): void
    {
        $this->loadModules();
        $this->loadThemes();
        $this->addModulesToThemes();
        $this->writeFiles();
    }

    private function loadModules(): void
    {
        $modules = $this->moduleList->getNames();
        array_walk($modules, function (string $module): void {
            $modulePath = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, $module);
            $jsonFilePath = $modulePath . '/view/frontend' . App::JSON_FILEPATH;
            if (file_exists($jsonFilePath)) {
                $this->loadModuleConfig($jsonFilePath, $module);
            }
        });
    }

    private function loadModuleConfig(string $jsonFilePath, string $module): void
    {
        $content = file_get_contents($jsonFilePath);
        $content = str_replace('"./', '"/' . $module . '/zermatt/', $content);

        $config = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        $this->modulesConfig[$module] = $config;
    }

    private function loadThemes(): void
    {
        $themes = $this->themePackageList->getThemes();
        array_walk($themes, function (ThemePackage $theme): void {
            $jsonFilePath = $theme->getPath() . App::JSON_FILEPATH;
            if (file_exists($jsonFilePath)) {
                $this->loadThemeConfig($jsonFilePath, $theme);
            }
        });
    }

    private function loadThemeConfig(string $jsonFilePath, ThemePackage $theme): void
    {
        $content = file_get_contents($jsonFilePath);
        $content = str_replace('"./', '"/zermatt/', $content);
        $this->themePaths[$theme->getKey()] = $theme->getPath();
        $this->themesConfig[$theme->getKey()] = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    private function addModulesToThemes(): void
    {
        foreach ($this->themesConfig as $theme => $themeConfig) {
            foreach ($this->modulesConfig as $moduleConfig) {
                foreach (['modules', 'rewrites'] as $section) {
                    $themeConfig[$section] = array_merge($moduleConfig[$section] ?? [], $themeConfig[$section] ?? []);
                }
            }

            $this->mergedThemes[$theme] = $themeConfig;
        }
    }

    private function writeFiles(): void
    {
        array_walk($this->mergedThemes, function ($themeConfig, $theme): void {
            $lockData = $this->prepareLockDataForTheme($themeConfig);
            $this->writeToFile($lockData, $theme);
        });
    }

    private function prepareLockDataForTheme(array $themeConfig): array
    {
        $lockData = [];
        foreach ($themeConfig as $section => $configs) {
            $lockData[$section] = $this->convertConfigsForLock($configs);
        }

        return $lockData;
    }

    private function convertConfigsForLock(array $configs): array
    {
        return array_map(static fn($key, $value): array => ['name' => $key, 'path' => $value], array_keys($configs), $configs);
    }

    private function writeToFile(array $lockData, string $theme): void
    {
        $filePath = $this->themePaths[$theme] . App::LOCK_FILEPATH;
        file_put_contents($filePath, json_encode($lockData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
