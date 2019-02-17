<?php

class Base_Form_Decorator_ElementErrors extends Zend_Form_Decorator_Abstract
{
    /**
     * @param string $content
     * @return string
     */
    public function render($content)
    {
        if (!$this->getElement()->hasErrors()) {
            return $content;
        }

        $options = $this->getOptions();
        $escape = true;
        if (isset($options['escape'])) {
            $escape = (bool) $options['escape'];
        }

        $errors = $this->getElement()->getMessages();
        if ($escape) {
            $view = $this->getElement()->getView();
            foreach ($errors as $key => $message) {
                $errors[$key] = $view->escape($message);
            }
        }

        $errormessage = trim(implode('. ', $errors));

        $content.= '<span class="fa fa-exclamation-triangle form-control-feedback"></span>';

        return $content . '<span class="text-danger">' . $errormessage . '</span>';
    }
}