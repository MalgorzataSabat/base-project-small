<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: FormFile.php 23775 2011-03-01 17:25:24Z ralph $
 */


/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/FormElement.php';


/**
 * Helper to generate a "file" element
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Base_View_Helper_FormFileImage extends Zend_View_Helper_FormElement
{
    /**
     * Generates a 'file' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formFileImage($name, $attribs = null)
    {
        $info = $this->_getInfo($name, null, $attribs);
        $imageCropper = 'imageCropper-'.$attribs['id'];
        extract($info); // name, id, value, attribs, options, listsep, disable

        // is it disabled?
        $disabled = '';
        if ($disable) {
            $disabled = 'disabled="disabled"';
        }

        // XHTML or HTML end tag?
        $endTag = ' />';
        if (($this->view instanceof Zend_View_Abstract) && !$this->view->doctype()->isXhtml()) {
            $endTag= '>';
        }

        $xhtml = '';

        $style= array('max-width'=>100,
                      'max-height'=>100,
                      'margin-top'=>1,
                      'border'=>'1px solid #eee'
            );

        foreach($style as $key=>$value){
            if(isset($attribs[$key])){
                $style[$key] = $attribs[$key];
            }
        }

        $style_disabled = false;
        $style_content = '';
        if(isset($attribs['disable_style']))
        {
            if ($attribs['disable_style']){
                $style_disabled = true;
            }
        }

        if(!$style_disabled)
        {
            foreach($style as $key=>$value){
                if($key!=='border'){
                   $style_content .= $key.':'.$value.'px;';
                }
                else
                {
                    $style_content.=$key.':'.$value;
                }
            }
        }

        $preview_image_id = 'preview-image_'.$name;

        if( isset($attribs['id_image']) && Image::checkImageExist($attribs['id_image']) ){
            $xhtml = '<img id="'.$preview_image_id.'" data-src="'.$attribs['image'].'" src="'.$attribs['image'].'" align="left" style="'.$style_content.'"'.$endTag;
            unset( $attribs['image'] );
        }

        if( isset( $attribs['delete'] ) && $attribs['delete'] ){
            unset( $attribs['delete'] );
            $xhtml.= '<a
                title="' . $this->view->translate('label_delete') . '"
                class="confirmModal"
                data-ajax="on"
                data-ajax-remove="#control_'.$preview_image_id.'"
                data-gritter-title="'.$this->view->translate('label_gritter_std-notifi').'"
                data-gritter-text="'.$this->view->translate('label_service-image_delete-success').'"
                href="'.$this->view->url( array(
                    'id_image'=> $attribs['id_image'],
                    'token'=> Base_SecurityToken::get($attribs['id_image'], 'image_delete'),
                    ), 'image_delete', true).'"
                ><i class="fa fa-remove"></i>
            </a><br/>';

        }

        if(isset($attribs['crop']) && $attribs['crop']){
            unset( $attribs['crop'] );
            $xhtml.= '<a
                id="'.$imageCropper.'"
                data-fancybox-type="iframe"
                title="' . $this->view->translate('label_crop') . '"
                href="'.$this->view->url( array(
                    'id_image'=> $attribs['id_image'],
                    'token'=> Base_SecurityToken::get($attribs['id_image'], 'image_cropper'),
//                    'imageParams'=>  json_encode(array(
//                        'minSize' => array(180,150),
//                        'aspectRatio'=> 1.2
//                    ))
                ), 'image_cropper', true).'?preview_image_id='.$preview_image_id.'"
            ><i class="fa fa-fullscreen"></i></a>';
        }

        if( !empty( $xhtml ) ){
            $xhtml = '<div id="control_'.$preview_image_id.'">'.$xhtml.'</div>';
        }


        return $xhtml;
    }
}
