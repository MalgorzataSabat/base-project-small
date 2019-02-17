<?php


class Base_I18n
{

    private static $first_run = false;

    private static $language_code = null;
    private static $locale_code = null;
    private static $language_id = null;

    /**
     * @var array()
     */
    private static $loaded_languages = null;

    /**
     * @var array()
     */
    private static $available_languages = null;

    /**
     * @var Zend_Session_Namespace
     */
    private static $settings = null;



    /**
     * Method discover current vertion of i18n and locale from web request.
     * Method can be run only once in browser request life cycle.
     *
     * @static
     * @param Zend_Controller_Request_Abstract $request
     * @throws Exception
     */
    public static function setRequest(Zend_Controller_Request_Abstract $request)
    {
        if (self::$first_run !== false) {
            throw new Exception('Base_I18n::setRequest is allowed to run only once.');
        }

        // getting provided languages from DB
        $domain = preg_replace('/www.(.*)/', '$1',$request->getHttpHost());
        self::$settings = new Zend_Session_Namespace('languages');
        self::loadAvailableLanguages();

        // get language from request uri, example: domain.com/en/
        $req_lang = array_filter(explode('/', trim($request->getRequestUri(),'/')));
        if(isset($req_lang[0]) && array_key_exists($req_lang[0], self::$loaded_languages['languages'])){
            self::$language_code = $req_lang[0];
            Zend_Controller_Front::getInstance()->setBaseUrl('/'.self::$language_code);

        // get lang from user settings
        }elseif(false != ($user_code = self::getUserLangCode())){
            self::$language_code = $user_code;

        // get default language for domain
        }elseif(isset(self::$loaded_languages['domain'][$domain])){
            self::$language_code = self::$loaded_languages['domain'][$domain];
        }


        // get default language for application
        if(empty(self::$language_code)){
            self::$language_code = self::getMainLangCode();
        }

        self::$language_id = self::$loaded_languages['languages'][self::$language_code]['id_language'];

        // set locale
        self::$locale_code = Base_Auth::getUser('locale');
        if(empty(self::$locale_code)){
            self::$locale_code = 'pl_PL';
        }


        $zend_locale = new Zend_Locale();
        $zend_locale->setDefault(self::$locale_code);

        // set some settings about lang to framework components
        $zend_locale->setLocale(self::$locale_code);


        try {
            // get translations from DB (already cached in model)
            $translate_options = array(
                'adapter' => 'array',
                'content' => Label::getLabels(self::$language_id, Label::TYPE_LABEL),
                'locale' => self::$locale_code,
                'logUntranslated' => false,
            );

            if(!DEV){
                $writer = new Zend_Log_Writer_Stream( APPLICATION_DATA . '/logs/untranslated.log');
                $log = new Zend_Log($writer);
                $translate_options['log'] = $log;
                $translate_options['logUntranslated'] = false;
            }

            $zend_translate = new Zend_Translate($translate_options);
            $zend_translate->setLocale(self::$locale_code);

            // translation for routing system
            $translate_options['content'] = Label::getLabels(self::$language_id, Label::TYPE_ROUTE);
            $translate_options['logUntranslated'] = false;
            $route_translate = new Zend_Translate($translate_options);
            Zend_Controller_Router_Route::setDefaultTranslator($route_translate);

            // standard framework components
            Zend_Validate_Abstract::setDefaultTranslator($zend_translate);
            Zend_Form::setDefaultTranslator($zend_translate);

            // for view helpers
            Zend_Registry::set('Zend_Translate', $zend_translate);
            Zend_Registry::set('Zend_Locale', $zend_locale);

        } catch (Exception $e) {
            if (DEV) throw $e;
        }

        //set default application timezone
        $timezone = 'Europe/Warsaw';
        if($timezone){
            date_default_timezone_set($timezone);
        }

        self::$first_run = true;
    }

    /**
     * @static
     * @return string
     */
    public static function getAvailableLanguages()
    {
        if(null === self::$available_languages){
            self::$available_languages = Language::getAvailableLanguages();
        }

        return self::$available_languages ;
    }


    /**
     * @return array|null
     */
    public static function getDomainLanguages()
    {
        $domain_languages = array();
        $languages = self::getAvailableLanguages();

        foreach($languages as $code => $lang){
            if(!empty($lang['domain'])){
                $domain_languages[$lang['domain']] = $code;
            }
        }

        return $domain_languages;
    }

    /**
     * @return int|null|string
     */
    public static function getMainLanguage()
    {
        $main = null;
        $languages = self::getAvailableLanguages();

        foreach($languages as $code => $lang){
            if($lang['is_main']){
                $main = $code;
                break;
            }
        }

        return $main;
    }


    /**
     * @return array
     */
    public static function getIdsLanguages()
    {
        $ids_languages = array();
        $languages = self::getAvailableLanguages();

        foreach($languages as $code => $lang){
            $ids_languages[$lang['id_language']] = $code;
        }


        return $ids_languages;
    }



    public static function loadAvailableLanguages()
    {
        if(self::$loaded_languages === null){
            $cache = Base_Cache::getCache();
            $cache_name = self::getCacheName();
            self::$loaded_languages = $cache->load($cache_name);

            if(false === self::$loaded_languages){
                self::$loaded_languages['languages'] = self::getAvailableLanguages();
                self::$loaded_languages['domain'] = self::getDomainLanguages();
                self::$loaded_languages['main'] = self::getMainLanguage();
                self::$loaded_languages['ids'] = self::getIdsLanguages();

                $cache->save(self::$loaded_languages, $cache_name);
            }
        }


        return self::$loaded_languages;
    }



    public static function isLangEnabled()
    {
        $langs = self::$loaded_languages;

        return count($langs['languages']) > 1 ? true : false;
    }


    public static function getLinkCode($code = null)
    {
        if(null === $code){
            $code = self::getLangCode();
        }

        return $code != self::getMainLangCode() ? '/'.$code : '';
    }


    public static function getCacheName()
    {
        return 'loaded_languages';
    }



    public static function cleanCache()
    {
        $cache = Base_Cache::getCache();
        $cache_name = self::getCacheName();
        $cache->clean($cache_name);
        self::$loaded_languages = null;

        self::loadAvailableLanguages();
    }


    /**
     * @static
     * @return string
     */
    public static function getUserLangCode()
    {
        $code = null;
        $id_language = Base_Auth::getUser('id_language');
        if($id_language){
            $code = self::getLangCodeById($id_language);
        }

        return $code;
    }


    public static function setContentLanguage($code = '')
    {
        if(!isset(self::$settings->content_locale_code)){
            self::$settings->content_locale_code = self::$locale_code;
            self::$settings->content_language_code = self::$language_code;
            self::$settings->content_language_id = self::$language_id;
        }

        if(!empty($code) && isset(self::$loaded_languages['languages'][$code])){
            self::$language_id = self::$loaded_languages['languages'][$code]['id_language'];
            self::$language_code = $code;

            self::$settings->content_locale_code = self::$locale_code;
            self::$settings->content_language_code = self::$language_code;
            self::$settings->content_language_id = self::$language_id;
        }else{
            self::$language_id = self::$settings->content_language_id;
            self::$language_code = self::$settings->content_language_code;
        }
    }


    /**
     * @param $code
     * @return mixed
     */
    public static function getLangNameByCode($code)
    {
        if (!isset(self::$loaded_languages['languages'][$code])) return false;
        return self::$loaded_languages['languages'][$code]['name'];
    }

    /**
     * @param $code
     * @return mixed
     */
    public static function getLangIdByCode($code)
    {
        if (!isset(self::$loaded_languages['languages'][$code])) return false;
        return self::$loaded_languages['languages'][$code]['id_language'];
    }


    /**
     * @param $id
     * @return bool|integer
     */
    public static function getLangCodeById($id)
    {
        if (!isset(self::$loaded_languages['ids'][$id])) return false;
        return self::$loaded_languages['ids'][$id];
    }

    /**
     * @static
     * @return string
     */
    public static function getLangCode()
    {
        return self::$language_code;
    }

    /**
     * @static
     * @return string
     */
    public static function getLangId()
    {
        return self::$language_id;
    }

    /**
     * @static
     * @param null $column
     * @return mixed
     */
    public static function getLang($column = null)
    {
        if (!empty($column)) {
            return self::$loaded_languages['languages'][self::$language_code][$column];
        }

        return  self::$loaded_languages['languages'][self::$language_code];
    }


    public static function getMainLangCode()
    {
        return  self::$loaded_languages['main'];
    }

    /**
     * @static
     * @param null $column
     * @return mixed
     */
    public static function getMainLang($column = null)
    {
        if (!empty($column)) {
            return self::$loaded_languages['languages'][self::getMainLangCode()][$column];
        }

        return self::$loaded_languages['languages'][self::getMainLangCode()];
    }


    /**
     * @return array
     */
    public static function getLocaleList()
    {
        // Init default locale
        $localeMultiKeys = array_merge(
            array_keys(Zend_Locale::getLocaleList())
        );
        $localeMultiOptions = array();
        $languages = Zend_Locale::getTranslationList('language', null);
        $territories = Zend_Locale::getTranslationList('territory', null);

        foreach($localeMultiKeys as $key)
        {
            if (!empty($languages[$key]))
            {
                $localeMultiOptions[$key] = $languages[$key];
            }
            else
            {
                $locale = new Zend_Locale($key);
                $region = $locale->getRegion();
                $language = $locale->getLanguage();
                if ((!empty($languages[$language]) && (!empty($territories[$region])))) {
                    $localeMultiOptions[$key] =  $languages[$language] . ' (' . $territories[$region] . ')';
                }
            }
        }
        $localeMultiOptions = array_merge(array('auto'=>'[Automatic]'), $localeMultiOptions);

        return $localeMultiOptions;
    }



}