<?php
/**
 * #PHPHEADER_OEFACEBOOK_LICENSE_INFORMATION#
 */

use OxidEsales\Eshop\Core\Registry;

/**
 * Class defines what module does on Shop events.
 */
class oeFacebookEvents
{
    const FBID_COLUMN_NAME = 'OEFACEBOOKFBID';
    const FB_CONTENTS_LOADID = 'oefacebookenableinfotext';

    /**
     * Add field oxuser.OEFACEBOOKFBID.
     */
    public static function addFacebookToUserTable()
    {
        $metaDataHandler = oxNew('OxidEsales\Eshop\Core\DbMetaDataHandler');
        if ($metaDataHandler->fieldExists(self::FBID_COLUMN_NAME, 'oxuser')) {
            return;
        }

        $parameters = array('column' => self::FBID_COLUMN_NAME);
        $query = oeFacebookEvents::getQuery('install_user.sql.tpl', $parameters);
        if (!empty($query)){
            oxDb::getDb()->execute($query);
        }
    }

    /**
     * Remove field oxuser.OEFACEBOOKFBID.
     */
    public static function removeFacebookFromUserTable()
    {
        $metaDataHandler = oxNew('OxidEsales\Eshop\Core\DbMetaDataHandler');
        if (!$metaDataHandler->fieldExists(self::FBID_COLUMN_NAME, 'oxuser')) {
            return;
        }

        $parameters = array('column' => self::FBID_COLUMN_NAME);
        $query = oeFacebookEvents::getQuery('uninstall_user.sql.tpl', $parameters);

        if (!empty($query)){
            oxDb::getDb()->execute($query);
        }

    }

    /**
     * Add Facebook lugins information.
     */
    public static function addFacebookContent()
    {
        if (self::doesContentExist()) {
            return;
        }

        $parameters = array('oxid' => oxUtilsObject::getInstance()->generateUid(),
                            'loadid' => self::FB_CONTENTS_LOADID,
                            'shopid' => Registry::getConfig()->getShopId());

        $query = oeFacebookEvents::getQuery('install_content.sql.tpl', $parameters);

        if (!empty($query)){
            oxDb::getDb()->execute($query);
        }
    }

    /**
     * Remove Facebook plugins information.
     */
    public static function removeFacebookContent()
    {
        $parameters = array('loadid' => self::FB_CONTENTS_LOADID,
                            'shopid' => Registry::getConfig()->getShopId());

        $query = oeFacebookEvents::getQuery('uninstall_content.sql.tpl', $parameters);

        if (!empty($query)){
            oxDb::getDb()->execute($query);
        }
    }

    /**
     * Execute action on activate event
     *
     * @return null
     */
    public static function onActivate()
    {
        self::addFacebookToUserTable();
        self::addFacebookContent();
    }

    /**
     * Execute action on deactivate event
     *
     * @return null
     */
    public static function onDeactivate()
    {
        self::clearTmp();
        //self::removeFacebookFromUserTable();
        //self::removeFacebookContent();
    }

    /**
     * Check if content with the given loadid already exists.
     * @return bool
     */
    public static function doesContentExist()
    {
        $content = oxNew('OxidEsales\Eshop\Application\Model\Content');
        $result = $content->loadByIdent(oeFacebookEvents::FB_CONTENTS_LOADID);

        return (bool) $result;
    }

    /**
     * Get content from file
     *
     * File must be placed in docs directory. If file with your given name + .tpl exist then
     * it will be processed with Smarty with publicly available object of $oModule with
     * this object instance
     *
     * @param string $filename
     * @parame array $parameters
     *
     * @return string
     */
    public static function getQuery($filename, $parameters)
    {
        $filePath = dirname(__FILE__) . '/../docs/' . (string) $filename;
        $result = '';

        if (file_exists($filePath)) {
            $smarty = Registry::get('oxUtilsView')->getSmarty();
            foreach ($parameters as $key => $value) {
                $smarty->assign($key, $value);
            }
            $result = $smarty->fetch($filePath);
        }

        return $result;
    }

    /**
     * Clean temp folder content.
     *
     * @param string $sClearFolderPath Sub-folder path to delete from. Should be a full, valid path inside temp folder.
     *
     * @return boolean
     */
    public static function clearTmp($sClearFolderPath = '')
    {
        $sFolderPath = self::_getFolderToClear($sClearFolderPath);
        $hDirHandler = opendir($sFolderPath);

        if (!empty($hDirHandler)) {
            while (false !== ($sFileName = readdir($hDirHandler))) {
                $sFilePath = $sFolderPath . DIRECTORY_SEPARATOR . $sFileName;
                self::_clear($sFileName, $sFilePath);
            }

            closedir($hDirHandler);
        }

        return true;
    }

    /**
     * Check if provided path is inside eShop `tpm/` folder or use the `tmp/` folder path.
     *
     * @param string $sClearFolderPath
     *
     * @return string
     */
    protected static function _getFolderToClear($sClearFolderPath = '')
    {
        $sTempFolderPath = (string) oxRegistry::getConfig()->getConfigParam('sCompileDir');

        if (!empty($sClearFolderPath) and (strpos($sClearFolderPath, $sTempFolderPath) !== false)) {
            $sFolderPath = $sClearFolderPath;
        } else {
            $sFolderPath = $sTempFolderPath;
        }

        return $sFolderPath;
    }

    /**
     * Check if resource could be deleted, then delete it's a file or
     * call recursive folder deletion if it's a directory.
     *
     * @param string $sFileName
     * @param string $sFilePath
     */
    protected static function _clear($sFileName, $sFilePath)
    {
        if (!in_array($sFileName, array('.', '..', '.gitkeep', '.htaccess'))) {
            if (is_file($sFilePath)) {
                @unlink($sFilePath);
            } else {
                self::clearTmp($sFilePath);
            }
        }
    }
}
