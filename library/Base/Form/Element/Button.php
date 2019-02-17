<?php

class Base_Form_Element_Button extends Zend_Form_Element_Button
{
    // Icon Positions
    const ICON_POSITION_LEFT = 'left';

    const ICON_POSITION_RIGHT = 'right';

    /**
     * Use formButton view helper by default
     *
     * @var string
     */
    public $helper = 'formButton';

    /**
     * The icon class, that will be added if needed
     *
     * @var string
     */
    private $_icon;

    private $_iconPosition = self::ICON_POSITION_LEFT;

    public function __construct ($spec, $options = null)
    {
        if (!isset($options['class'])) {
            $options['class'] = '';
        }

        $classes = explode(' ', $options['class']);
        $classes[] = 'btn';

        if (isset($options['btnClass'])) {
            $classes[] = 'btn-' . $options['btnClass'];
            unset($options['btnClass']);
        }else{
            $classes[] = 'btn-primary';
        }

        if ( isset($options['icon']) && !empty($options['icon']) ) {
            $classes[] = 'btn-labeled';
        }

        $classes = array_unique($classes);
        $options['class'] = trim( implode(' ', $classes) );



        if (isset($options['icon']) && !empty($options['icon'])) {
            // Disable automatic label escaping
            $options['escape'] = false;
            
            $this->_icon = $options['icon'];
            
            if (isset($options['iconPosition'])) {
                if (strcmp($options['iconPosition'], self::ICON_POSITION_RIGHT) === 0) {
                    $this->_iconPosition = self::ICON_POSITION_RIGHT;
                }
                unset($options['iconPosition']);
            }
            
            unset($options['icon']);
        }

        parent::__construct($spec, $options);
    }

    /**
     * Renders the icon tag
     *
     * @return string
     */
    private function _renderIcon ()
    {
        return !empty($this->_icon) ? '<div class="btn-label"><i class="fa fa-' . $this->_icon . '"></i></div>' : '';
    }

    /**
     * Gets the button label
     *
     * @return string
     */
    public function getLabel ()
    {
        // Render the icon on either side
        if (strcasecmp($this->_iconPosition, self::ICON_POSITION_LEFT) === 0) {
            return $this->_renderIcon() . PHP_EOL . parent::getLabel();
        }
        
        return parent::getLabel() . PHP_EOL . $this->_renderIcon();
    }
}
