<?php
/**
 * Process Manager.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2016 lineofcode.at (http://www.lineofcode.at)
 * @license    https://github.com/dpfaffenbauer/ProcessManager/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace ProcessManager;

use Pimcore\API\Plugin as PluginLib;
use Pimcore\Db;
use Pimcore\Model\Schedule\Maintenance\Job;
use Pimcore\Model\Schedule\Manager\Procedural;

/**
 * Pimcore Plugin
 *
 * Class Plugin
 * @package ProcessManager
 */
class Plugin extends PluginLib\AbstractPlugin implements PluginLib\PluginInterface
{
    /**
     * @var \Zend_Translate
     */
    protected static $_translate;

    /**
     *
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @return string
     */
    public static function install()
    {
        $db = Db::get();
        $result = false;

        try
        {
            $result = $db->describeTable("plugin_process_manager");
        }
        catch(\Exception $e) {
        }

        if(!$result) {
            $db->query("CREATE TABLE `plugin_process_manager` (
              `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `name` VARCHAR(255) NOT NULL,
              `message` TEXT NOT NULL,
              `progress` int NOT NULL,
              `total` int NOT NULL
            );");
        }

        return self::getTranslate()->_('processmanager_installed');
    }

    /**
     * @return bool
     */
    public static function uninstall()
    {
        $db = Db::get();

        $db->query("DROP TABLE `plugin_process_manager`;");

        return self::getTranslate()->_('processmanager_uninstalled');
    }

    /**
     * indicates wether this plugins is currently installed
     * @return boolean
     */
    public static function isInstalled() {
        $result = null;

        try
        {
            $result = Db::get()->describeTable("plugin_process_manager");
        }
        catch(\Exception $e) {

        }

        return !empty($result);
    }

    /**
     * get translation directory.
     *
     * @return string
     */
    public static function getTranslationFileDirectory()
    {
        return PIMCORE_PLUGINS_PATH.'/ProcessManager/static/texts';
    }

    /**
     * get translation file.
     *
     * @param string $language
     *
     * @return string path to the translation file relative to plugin directory
     */
    public static function getTranslationFile($language)
    {
        if (is_file(self::getTranslationFileDirectory()."/$language.csv")) {
            return "/ProcessManager/static/texts/$language.csv";
        } else {
            return '/ProcessManager/static/texts/en.csv';
        }
    }

    /**
     * get translate.
     *
     * @param $lang
     *
     * @return \Zend_Translate
     */
    public static function getTranslate($lang = null)
    {
        if (self::$_translate instanceof \Zend_Translate) {
            return self::$_translate;
        }
        if (is_null($lang)) {
            try {
                $lang = \Zend_Registry::get('Zend_Locale')->getLanguage();
            } catch (\Exception $e) {
                $lang = 'en';
            }
        }

        self::$_translate = new \Zend_Translate(
            'csv',
            PIMCORE_PLUGINS_PATH.self::getTranslationFile($lang),
            $lang,
            array('delimiter' => ',')
        );

        return self::$_translate;
    }
}
