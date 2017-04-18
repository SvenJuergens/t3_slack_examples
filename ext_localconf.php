<?php

/** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class
);

//FEmanager -> new User
$signalSlotDispatcher->connect(
    \In2code\Femanager\Controller\AbstractController::class,
    'finalCreateAfterPersist',
    \SvenJuergens\T3SlackExamples\Utility\Examples::class,
    'feManagerNewUser',
    true
);

// query insert -hook for dm_developerlog
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_db.php']['queryProcessors'][] =
    \SvenJuergens\T3SlackExamples\Hooks\QueryHooker::class;
