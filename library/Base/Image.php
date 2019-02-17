<?php
/**
 * @category    Base
 * @package     Base_Image
 */

class Base_Image
{
    protected static $_adapterOptions = array(
        'preserveAlpha'         =>  true,
        'alphaMaskColor'        =>  array(255, 255, 255),
        'preserveTransparency'  =>  true,
        'transparencyMaskColor' =>  array(0, 0, 0),
        'resizeUp'              =>  true
    );
    
    /**
     * @param string $adapterClass
     * @param array $adapterOptions
     * @return Base_Image_Adapter_Abstract
     */
    public static function factory($adapterClass = null, $adapterOptions = null)
    {
        if (! $adapterClass) {
            if (extension_loaded('gd')) {
                $adapterClass = 'Base_Image_Adapter_GD';
            } else {
                $adapterClass = 'Base_Image_Adapter_ImageMagick';
            }
        }
        
        if (null === $adapterOptions) {
            $adapterOptions = self::$_adapterOptions;
        }
        
        return new $adapterClass($adapterOptions);
    }
}