<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\App;

use Magento\Framework\Escaper;
use Magento\Framework\Locale\Resolver;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Text;
use Magento\Framework\View\Element\TextFactory;
use Magento\Store\Model\StoreManager;

class Config implements ArgumentInterface
{
    private readonly Text $emptyBlock;

    public function __construct(
        protected readonly UrlInterface $url,
        protected readonly TextFactory  $blockFactory,
        protected readonly Resolver     $locale,
        protected readonly Escaper      $escaper,
        protected readonly StoreManager $storeManager
    )
    {
        $this->emptyBlock = $this->blockFactory->create();
    }

    public function get(string $key = null): mixed
    {
        $config = $this->_get();
        array_walk($config, fn(&$value) => $value = is_string($value) ? $this->escaper->escapeJs($value) : $value);
        return $key !== null && $key !== '' ? $config[$key] : $config;
    }

    /**
     * These variables are available on the frontend,
     * in the Zermatt.Variables object.
     */
    protected function _get(): array
    {
        return [
            'baseUrl' => $this->url->getUrl('/'),
            'viewUrl' => $this->emptyBlock->getViewFileUrl('/'),
            'translationUrl' => $this->emptyBlock->getViewFileUrl('/js-translation.json'),
            'locale' => $this->locale->getLocale(),
            'currency' => [
                'code' => $this->storeManager->getStore()->getCurrentCurrencyCode(),
                'symbol' => $this->storeManager->getStore()->getCurrentCurrency()->getCurrencySymbol()
            ]
        ];
    }
}
