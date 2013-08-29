<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\View\Helper;

use Zend\Form\Element\Textarea;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;
use Zend\Form\View\Helper\FormTextarea;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Exception\InvalidArgumentException;

class CKEditor extends FormTextarea
{
    protected $options = array(
        'script_src' => '//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.2/ckeditor.js',
    );

    protected $scriptSrcAppended = false;

    /**
     * {@inheritdoc}
     */
    public function __invoke(ElementInterface $element = null, $options = array())
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element, $options);
    }

    public function render(ElementInterface $element, $options = array())
    {
        if (! $element instanceof Textarea) {
            throw new InvalidArgumentException('Element must be an instance of Textarea');
        }

        $view = $this->getView();

        if (!method_exists($view, 'plugin')) {
            return parent::render($element);
        }

        $options = ArrayUtils::merge($this->options, $options);

        if (!$this->scriptSrcAppended) {
            $view->plugin('inline_script')->appendFile($options['script_src']);
            $this->scriptSrcAppended = true;
        }

        $this->getView()->inlineScript()->appendScript("CKEDITOR.replace( '{$element->getName()}' );");

        return parent::render($element);
    }

}
