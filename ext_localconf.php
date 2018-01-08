<?php
defined('TYPO3_MODE') or die();

$load = function ($_EXTKEY) {
    $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
    if (!empty($settings['includeJavaScript'])) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Resources/Private/TypoScript/javaScript.ts">');
    }

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT:source="FILE:EXT:' . $_EXTKEY . '/Configuration/PageTSconfig/NewContentElementWizard.ts">'
    );
};

$load($_EXTKEY);
unset($load);

