<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Zermatt\Component;

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
        $components = Component::all();
        array_walk($components, function (array $component) use (&$html): void {
            $html = str_replace($component['id'], $this->render($component), $html);
        });

        if (str_contains($html, Component::PLACEHOLDER)) {
            $html = $this->output($html);
        }

        return $html;
    }

    public function render(array $component): string
    {
        $this->templateBlock = $this->templateFactory->create();
        $this->templateBlock->setTemplate($component['template']);
        array_walk($component['props'], fn($value, $key) => $this->templateBlock->setData($key, $value));
        return $this->templateBlock->toHtml();
    }
}
