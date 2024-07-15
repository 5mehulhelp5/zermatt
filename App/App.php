<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\App;

use Magento\Framework\Component\ComponentRegistrarInterface as ComponentRegistrar;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class App implements ArgumentInterface
{
    final public const DIST_DIR = 'zermatt/dist/';

    final public const SRC_DIR = '/view/frontend/web/zermatt';

    final public const MANIFEST_FILEPATH = '.vite/manifest.json';

    final public const LOCK_FILEPATH = '/web/zermatt/zermatt-lock.json';

    final public const JSON_FILEPATH = '/web/zermatt/zermatt.json';

    public function __construct(
        protected readonly Repository         $assetRepo,
        protected readonly ComponentRegistrar $componentRegistrar
    )
    {
    }

    public function sourceDir(): string
    {
        return $this->componentRegistrar->getPath('module', 'Maddlen_Zermatt') . self::SRC_DIR;
    }

    public function entryFilepath(): string
    {
        $manifest = $this->assetRepo->createAsset(self::DIST_DIR . self::MANIFEST_FILEPATH);
        $manifestData = json_decode($manifest->getContent(), true, 512, JSON_THROW_ON_ERROR);
        return self::DIST_DIR . $manifestData['index.html']['file'];
    }
}
