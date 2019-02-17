<?php
class Base_EventManager
{
    /**
     * Rozgłasza o wystąpieniu zdarzenia globalnego.
     *
     * Parametry $options:
     *  id      - zazwyczaj identyfikator (PK) obiektu, którego dotyczy event
     *  object  - instancja danego obiektu, którego dotyczy event
     *  context - obiekt lub nazwa klasy kontekstu
     *
     * Jest to ekstremalnie proste rozwiązanie.
     * Umożliwia globalne rozgłaszanie i centralne sterowanie komunikatami.
     *
     * W przyszłości -> Zend_EventManager.
     *
     * @param string $event Nazwa zdarzenia
     * @param array $options Dodatkowe informacje
     */
   public static function trigger($event, $item)
    {
        if('imageDelete' == $event || 'imageUpdate' == $event) {
            // usunięcie cachu statycznego dla danego zdjęcia (miniaturki)
            Base_Cache::getCache('page')->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array( "image_".$item));
        }


        if('imageDelete' == $event) {
            // usunięcie listy zdjęć dla danego objektu
//            Base_Cache::getCache('page')->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('image_'.$item));
        }
    }
}
