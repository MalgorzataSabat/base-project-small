<?php
/**
 * @category    Base
 * @package     Base_Image
 * @subpackage  Adapter
 */
class Base_Image_Adapter_ImageMagick extends Base_Image_Adapter_Abstract
{   
   
    /**
    * List of accepted image types based on MIME
    * descriptions that this adapter supports
    */
    protected $_imgTypes = array(
        'image/jpeg',
        'image/jpg',
        'image/pjpeg',
        'image/png',
        'image/gif',
        'image/x-png'
    );
    
    public function __construct($options = array(), $scale = true, $inflate = true, $quality = self::DEFAULT_QUALITY)
    {
        if (!extension_loaded('imagick')) {
          throw new Base_Exception ('Imagick not enabled. Check your php.ini file.');
        }
        
        parent::__construct($options, $scale, $inflate, $quality);
    }
    
    /**
     * @param $image string
     * @return Base_Image_Adapter_ImageMagick
     */
    public function open($image)
    {
        $information = @getimagesize($image);
        if (!$information) {
            throw new Base_Exception(sprintf('Could not load image %s', $image));
        }

        if (!in_array($information['mime'], $this->_imgTypes)) {
            throw new Base_Exception(sprintf('Image MIME type %s not supported', $information['mime']));
        }

        $this->_source = new Imagick($image);
        $this->_thumb = $this->_source;
        $this->_sourceWidth = $information[0];
        $this->_sourceHeight = $information[1];
        $this->_sourceMime = $information['mime'];
        
        $this->_sourceSrc = $image;
        return $this;
    }
    
    
    public function load($image, $mime)
    {
        $this->_source = new Imagick($image);
        
        $this->_reloadSize();
        $this->_sourceMime = $mime;
        $this->_generateThumb();
        
        return $this;
    }
    
    public function save($dest, $targetMime = null)
    {
        $dirname = dirname($dest);
        
        if (!file_exists($dirname)) {
            throw new Base_Exception(sprintf('Directory %s does not exist', $dirname));
        }
        
        if (!is_writable($dirname)) {
            throw new Base_Exception(sprintf('File %s is not writable', $dirname));
        }
        
        if (!$this->_hasBeenModified && $this->_sourceSrc !== null) {
            return copy($this->_sourceSrc, $dest);
        }
               
        return $this->_thumb->writeImage($dest);
    }
    
    public function generatePerspective($points, $json = TRUE, $disort_type = imagick::DISTORTION_PERSPECTIVE){
        if($json)
            $points = json_decode($points);
        $this->_thumb->setImageFormat('png');
        $this->_thumb->setImageMatte(true);
        $this->_thumb->setImageVirtualPixelMethod( imagick::VIRTUALPIXELMETHOD_TRANSPARENT );
        $this->_thumb->distortImage( $disort_type, $points, TRUE );
        $this->_hasBeenModified = true;
        $this->_source = $this->_thumb;        
        $this->_reloadSize();
    }
    
    protected function _newThumb($width, $height)
    {
        $this->_thumb = $this->_source;        
        $this->_thumb->thumbnailImage($width , $height , TRUE);        
        $this->_hasBeenModified = true;
        return $this;
    }
    
    protected function _copyToThumb($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
    {     
        $this->_thumb->cropImage( $dst_w, $dst_h, $dst_x, $dst_y );        
        return $this;
    }
    
    protected function _reloadSize()
    {
        $geometry = $this->_source->getImageGeometry();
        $this->_sourceWidth = $geometry['width'];
        $this->_sourceHeight = $geometry['height'];
        
        return $this;
    }

}