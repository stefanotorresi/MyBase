<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Stdlib\ArrayUtils;
use ZfcTwitterBootstrap\Form\View\Helper\FormElement;

class BootstrapFormFile extends FormElement
{
    protected $options = [
        'script_src' => 'http://cdn.add-design.it/js/jasny-bootstrap/2.3.1-j6/bootstrap-fileupload.min.js',
        'css_src' => 'http://cdn.add-design.it/js/jasny-bootstrap/2.3.1-j6/bootstrap-fileupload.min.css',
    ];

    protected $assetsAppended = false;

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function render(ElementInterface $element, $groupWrapper = null, $controlWrapper = null, $options = [])
    {
        $view = $this->getView();

        if (! method_exists($view, 'plugin')) {
            return parent::render($element, $groupWrapper, $controlWrapper);
        }

        $options = ArrayUtils::merge($this->getOptions(), $options);

        $translateHelper = $view->plugin('translate');
        $headLinkHelper = $view->plugin('headLink');
        $inlineScriptHelper = $view->plugin('inlineScript');

        $browseLabel = $translateHelper("Browse");
        $replaceLabel = $translateHelper("Replace");
        $removeLabel = $translateHelper("Remove");

        $containerClass = $element->getValue() ? 'exists' : 'new';

        $this->controlWrapper = <<<EOD
<div class="controls" id="controls-%s">
    <div class="fileupload fileupload-$containerClass" data-provides="fileupload">
        <div class="input-append">
            <div class="uneditable-input span2">
                <i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span>
            </div>
            <span class="btn btn-file">
                <span class="fileupload-new">$browseLabel</span>
                <span class="fileupload-exists">$replaceLabel</span>
                %s
            </span>
            <a href="#" class="btn btn-file-remove fileupload-exists" data-dismiss="fileupload">$removeLabel</a>
        </div>
    </div>
    %s
    %s
</div>
EOD;

        $groupWrapper = $groupWrapper ?: $this->groupWrapper;
        $controlWrapper = $controlWrapper ?: $this->controlWrapper;

        if (! $this->assetsAppended) {
            $headLinkHelper()->appendStylesheet($options['css_src'], 'all');
            $inlineScriptHelper()->appendFile($options['script_src']);
            $this->assetsAppended = true;
        }

        return parent::render($element, $groupWrapper, $controlWrapper);
    }

}
