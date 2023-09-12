<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "dena_charts
 *
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Charts',
    'description' => 'Charts allows two display data sets as charts.',
    'author' => 'Dirk Wenzel',
    'author_email' => 'dirk.wenzel@cps-it.de',
    'author_company' => '',
    'state' => 'beta',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'version' => '1.0.2',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '10.4.0-11.5.99'
                ],
            'conflicts' => [],
            'suggests' => [],
        ],
];
