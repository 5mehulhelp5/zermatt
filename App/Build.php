<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\App;

use Magento\Framework\View\Design\Theme\ThemePackage;
use Magento\Framework\View\Design\Theme\ThemePackageList;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Build
{
    private array $zermattThemes;
    private ?string $themeToBuild;

    public function __construct(
        protected readonly ThemePackageList $themePackageList,
    )
    {
    }

    public function themes(?string $themeToBuild = null): void
    {
        $this->themeToBuild = $themeToBuild;

        echo "\n" . 'Starting Zermatt build.' . "\n";

        $this->findZermattThemes();
        $this->build();

        echo "\n" . 'Finished Zermatt build.' . "\n\n";
    }

    private function findZermattThemes(): void
    {
        $themes = $this->themePackageList->getThemes();
        $this->zermattThemes = array_filter($themes, function (ThemePackage $theme) {
            $jsonFilePath = $theme->getPath() . App::JSON_FILEPATH;
            $themeName = sprintf('%s/%s', $theme->getVendor(), $theme->getName());
            return file_exists($jsonFilePath) && ($themeName === $this->themeToBuild || !$this->themeToBuild);
        });
    }

    private function build(): void
    {
        if (count($this->zermattThemes) < 1) {
            echo 'No Zermatt-enabled theme found.';
        }

        array_walk($this->zermattThemes, function (ThemePackage $theme) {
            echo sprintf('Building Zermatt theme %s.', $theme->getKey()) . "\n";
            $processes = $this->makeProcesses($theme);
            array_walk($processes, function ($processConfig) {
                $this->buildTheme($processConfig);
            });
        });
    }

    private function makeProcesses($theme): array
    {
        $workDir = $theme->getPath() . App::THEME_DIR;
        return [
            'install' => [
                'process' => new Process(['npm', 'install'], $workDir),
                'output' => false,
                'message' => __('Installing npm dependencies.'),
            ],
            'build' => [
                'message' => __('Building Zermatt app.'),
                'process' => new Process(['npm', 'run', 'build'], $workDir),
                'output' => true
            ]
        ];
    }

    private function buildTheme($processConfig): void
    {
        echo $processConfig['message'] . "\n";
        $process = $processConfig['process'];
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        if ($processConfig['output']) {
            echo $process->getOutput();
        }
    }
}
