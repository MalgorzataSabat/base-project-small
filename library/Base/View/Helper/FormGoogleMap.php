<?php

class Base_View_Helper_FormGoogleMap extends Zend_View_Helper_FormTextarea {

    public function formGoogleMap($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info);

        if ( empty( $value ) ) {
            $value = '';
        }

        $xhtml = '<div id="map-canvas" style="width: 100%; height: 350px;"></div>';
        return $xhtml;
    }
}