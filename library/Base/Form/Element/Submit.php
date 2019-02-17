<?php

class Base_Form_Element_Submit extends Zend_Form_Element_Submit
{
    const BUTTON_DEFAULT = 'default';
    const BUTTON_PRIMARY = 'primary';
    const BUTTON_INFO = 'info';
    const BUTTON_SUCCESS = 'success';
    const BUTTON_WARNING = 'warning';
    const BUTTON_DANGER = 'danger';
    const BUTTON_INVERSE = 'inverse';
    const BUTTON_LINK = 'link';

    protected $buttons = array(
        self::BUTTON_DEFAULT,
        self::BUTTON_DANGER,
        self::BUTTON_INFO,
        self::BUTTON_PRIMARY,
        self::BUTTON_SUCCESS,
        self::BUTTON_WARNING,
        self::BUTTON_INVERSE,
        self::BUTTON_LINK
    );

    /**
     * Class constructor
     *
     * @param $spec
     * @param array $options
     */
    public function __construct($spec, $options = null)
    {

        if (!isset($options['class'])) {
            $options['class'] = '';
        }


        $classes = explode(' ', $options['class']);
        $classes[] = 'btn submit';

        if (isset($options['buttonType']) && in_array( $options['buttonType'], $this->buttons )) {
            $classes[] = 'btn-' . $options['buttonType'];
            unset($options['buttonType']);
        }else{
            $classes[] = 'btn-' . self::BUTTON_SUCCESS;
        }

        if (isset($options['disabled'])) {
            $classes[] = 'disabled';
        }

        $classes = array_unique($classes);
        $options['class'] = trim( implode(' ', $classes) );

        if(!isset($options['label'])){ $options['label'] = 'Zapisz'; }

        $options['data-sending-message'] = $this->getView()->translate('cms_button_sending');

        parent::__construct($spec, $options);

    }
}

