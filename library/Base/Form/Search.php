<?php

final class Base_Form_Search extends Base_Form_Inline
{
    public function __construct($options = null)
    {
        $this->_initializePrefixes();
        
        $this->setDisposition(self::DISPOSITION_SEARCH);

        $renderButton = true;
        if (isset($options['renderInNavBar']) && true === $options['renderInNavBar']) {
            $this->_removeClassNames('form-search');
            $classes = array('navbar-search');
            if (isset($options['pullItRight']) && true === $options['pullItRight']) {
                $classes[] = 'pull-right';
                unset($options['pull-right']);
            }

            $this->_addClassNames($classes);
            unset($options['renderInNavBar']);
            $renderButton = false;
        }

        // Add the search input
        $inputName = isset($options['inputName']) ? $options['inputName'] : 'searchQuery';
        $placeholder = isset($options['placeholder']) ? $options['placeholder'] : null;
        $this->addElement('text', $inputName, array(
            'class' => 'search-query',
            'placeholder' => $placeholder
        ));

        if ($renderButton) {
            $buttonLabel = isset($options['submitLabel']) ? $options['submitLabel'] : 'Submit';
            $this->addElement('submit', 'submit', array(
                'class' => 'btn',
                'label' => $buttonLabel
            ));
        }
        
        parent::__construct($options);
    }
}
