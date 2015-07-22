<?php
    /**
     * Bu Dosya AnonymFramework'e ait bir dosyadır.
     *
     * @author vahitserifsaglam <vahit.serif119@gmail.com>
     * @see http://gemframework.com
     *
     */

    namespace Anonym\Components\Config;
    use ArrayAccess;
    /**
     * Class Reposity
     * @package Anonym\Components\Config
     */
    class Reposity implements  ArrayAccess
    {
        /**
         * Ayar verilerini tutar
         *
         * @var array
         */
        private static $configs;

        /**
         * Değeri döndürür
         *
         * @param string $name
         * @return mixed
         */
        public static function get($name = ''){
            $parse = static::parse($name);
            if (count($parse) === 1) {
                $task = $parse[0];
            } elseif (count($parse) === 2) {
                list($task, $method) = $parse;
            } elseif (count($parse) === 3) {
                list($task, $method, $fname) = $parse;
            }
            if (isset(static::$configs[$task])) {
                $return = static::$configs[$task];
            } else {
                return null;
            }
            if (isset($method)) {
                if (isset($return[$method])) {
                    $return = $return[$method];
                    if (isset($fname)) {
                        if (isset($return[$fname])) {
                            $return = $return[$fname];
                        }
                    }
                }
            }
            return $return;
        }

        /**
         * Veriye yeni değer ataması yapar
         *
         * @param string $name
         * @param string $value
         * @return bool
         */
        public static function set($name = '', $value = ''){
            if (!strstr($name, ".")) {
                static::$configs[$name] = $value;
            } else {
                $parse = static::parse($name);
                if (count($parse) === 2) {
                    list($name, $fname) = $parse;
                    static::$configs[$name][$fname] = $value;
                } elseif (count($parse) === 3) {
                    list($name, $fname, $sname) = $parse;
                    static::$configs[$name][$fname][$sname] = $value;
                }
            }

            return true;
        }


        /**
         * @param string $name eklenecek değerin ismi
         * @param string $value değeri
         */
        public static function add($name = '', $value = '')
        {
            if (!strstr($name, ".")) {
                static::$configs[$name] = array_merge(static::$configs[$name], [$value]);
            } else {
                $parse = static::parse($name);
                if (count($parse) === 2) {
                    list($name, $fname) = $parse;
                    static::$configs[$name][$fname] = array_merge(static::$configs[$name][$fname][], [$value]);
                } elseif (count($parse) === 3) {
                    list($name, $fname, $sname) = $parse;
                    static::$configs[$name][$fname][$sname] = array_merge(static::$configs[$name][$fname][$sname], [$value]);
                }
            }
        }

        /**
         * Veri varsa siler yoksa boş geçer
         *
         * @param string $name
         * @return bool
         */
        public  static  function delete($name = ''){
            return static::set($name, null);
        }

        /**
         * Ayar varmı yokmu diye kontrol eder
         *
         * @param string $name
         * @return mixed
         */
        public static function has($name = ''){
            $get = static::get($name);
            if (null !== $get) {
                return $get;
            } else {
                return false;
            }
        }

        /**
         * @return array
         */
        public static function getConfigs()
        {
            return self::$configs;
        }

        /**
         * @param array $configs
         */
        public static function setConfigs($configs)
        {
            self::$configs = $configs;
        }

        /**
         * Metni parçalar
         *
         * @param string $config
         * @return array
         */
        private static function parse($config = ''){
            if (strstr($config, ".")) {
                $parse = explode('.', $config);
                return $parse;
            } else {
                return (array)$config;
            }
        }


        /**
         * Dizi olarak erişilirken itemin olup olmadığına bakılır
         *
         * @param  string $key
         * @return bool
         */
        public function offsetExists($key)
        {
            return static::has($key);
        }
        /**
         * Dizi olarak erişilirken Veri çekmekte kullanılır
         *
         * @param  string $key
         * @return mixed
         */
        public function offsetGet($key)
        {
            return static::get($key);
        }
        /**
         * Dizi olarak erişilirken veri eklemede kullanılır
         *
         * @param  string $key
         * @param  mixed $value
         * @return void
         */
        public function offsetSet($key, $value)
        {
            static::set($key, $value);
        }
        /**
         * Array olarak erişilirken veri unset edildiğinde yapılır
         *
         * @param  string $key
         * @return void
         */
        public function offsetUnset($key)
        {
            static::set($key, null);
        }
    }
