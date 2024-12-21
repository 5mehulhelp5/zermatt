<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\Partial;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\TemplateFactory;

class Render
{
    private Template $templateBlock;

    public function __construct(
        protected readonly TemplateFactory $templateFactory
    )
    {
    }

    public function output(string $html): string
    {
        $partials = Partial::all();
        array_walk($partials, function (array $partial) use (&$html): void {
            $html = str_replace($partial['id'], $this->render($partial), $html);
        });

        if (str_contains($html, Partial::PLACEHOLDER)) {
            $html = $this->output($html);
        }

        return $html;
    }

    public function render(array $partial): string
    {
        $this->templateBlock = $this->templateFactory->create();
        $template = str_contains($partial['template'], '.phtml') ? $partial['template'] : $partial['template'] . '.phtml';
        $this->templateBlock->setTemplate($template);
        array_walk($partial['props'], fn($value, $key) => $this->templateBlock->setData($key, $value));
        return $this->templateBlock->toHtml();
    }
}
