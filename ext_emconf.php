<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "dena_charts
 *
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Charts',
    'description' => 'Charts allows two display data sets as charts. This is a TYPO3 extension for the DENA project.',
    'author' => 'Dirk Wenzel',
    'author_email' => 'dirk.wenzel@cps-it.de',
    'author_company' => '',
    'state' => 'beta',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'version' => '0.1.0',
    'constraints' =>
        array(
            'depends' =>
                array(
                    'typo3' => '6.2.0-8.99.99',
                    'php' => '5.6.0-0.0.0',
                ),
            'conflicts' =>
                array(),
            'suggests' =>
                array(),
        ),
    '_md5_values_when_last_written' => 'foo',
);

