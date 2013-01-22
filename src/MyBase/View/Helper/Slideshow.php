<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace My\View\Helper;

use Fud\Model;
use Zend\View\Helper\AbstractHelper;

class Slideshow extends AbstractHelper
{
    /**
     *
     * @var Model\Slideshows
     */
    protected $slideshows;

    /**
     * 
     * @param SlideshowModel $slideshows
     */
    public function __construct(Model\Slideshows $slideshows)
    {
        $this->slideshows = $slideshows;
    }
    
    /**
     * 
     * @param string $name
     * @return string
     */
    public function __invoke($name = '', $options = array())
    {
        $basePath = $this->view->plugin('basePath');        
        $slides = $this->slideshows->getSlides($name);        
        $imgUri = $this->slideshows->getImgUri();
        
        $output = '<figure class="slideshow">';
        
        foreach ($slides as $slide) { /* @var $slide Model\Slide */
            
            $style = "background: ";
            
            if ($slide->bg) {
                $style .= "url('".$basePath($imgUri.$slide->bg)."') repeat-x";
            }
            
            if ($slide->bgColor) {
                $style .= $slide->bgColor;
            }
            
            $style .= ";";
            
            $output .= 
            '<div class="slide" style="'.$style.'">
                <img src="'.$basePath($imgUri.$slide->img).'" alt="">
            </div>';
        }
        
        $output .= '</figure>'; 
        
        return $output;
    }
}
