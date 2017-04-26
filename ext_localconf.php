<?php

/** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class
);

//FeManager - send a Message if a new User as completed the Registration Process
$signalSlotDispatcher->connect(
    \In2code\Femanager\Controller\AbstractController::class,
    'finalCreateAfterPersist',
    \SvenJuergens\T3SlackExamples\Slot\FemanagerFinalCreateAfterPersist::class,
    'feManagerNewUser',
    true
);

// Send a Message ig a new Extension is installed
$signalSlotDispatcher->connect(
    \TYPO3\CMS\Extensionmanager\Utility\InstallUtility::class,
    'afterExtensionInstall',
    \SvenJuergens\T3SlackExamples\Slot\ExtensionmanagerAfterExtensionInstall::class,
    'extensionmanagerAfterExtensionInstall',
    true
);

// query insert -hook for syslog, if a new syslog (Error) entry is inserted, send a new Message
// query update for scheduler task, if a Scheduler Task is ended with an error, send a message
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_db.php']['queryProcessors'][] =
    \SvenJuergens\T3SlackExamples\Hooks\QueryHooker::class;
