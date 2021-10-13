<?php
defined('TYPO3_MODE') or die();

$load = function ($_EXTKEY) {
    $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
    if (!empty($settings['includeJavaScript'])) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Resources/Private/TypoScript/javaScript.typoscript">');
    }

    // register backend icons
    (function(string $extKey) {
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconsBasePath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey) . 'Resources/Public/Backend/Icons';
        foreach (['ctype'] as $type) {
            $iconGlob = $iconsBasePath . '/' . $type . '/*.svg';
            foreach (glob($iconGlob) as $file) {
                $value = basename($file, '.svg');
                $iconRegistry->registerIcon(
                    sprintf('%s-%s-%s', $extKey, $type, $value),
                    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
                    ['source' => $file],
                );
            }
        }
    })('dena_charts');
};

$load($_EXTKEY);
unset($load);

