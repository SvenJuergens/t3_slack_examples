<?php
namespace SvenJuergens\T3SlackExamples\Slot;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use SvenJuergens\T3Slack\Service\T3Slack;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;

class ExtensionmanagerAfterExtensionInstall
{

    /**
     * Send a Message , if a new User has completed the Registration
     *
    /*
     * @param string $extensionKey kex which is uninstalled
     * @param  InstallUtility $parentObj
     * @param $signalInformation
     */
    public function extensionmanagerAfterExtensionInstall($extensionKey, $parentObj, $signalInformation)
    {
        $client = GeneralUtility::makeInstance(T3Slack::class);
        $extension = $parentObj->enrichExtensionWithDetails($extensionKey);
        try {
            $client->withIcon(':dizzy:')->attach([
                'fallback' => 'new Extension:' . $extension['title'],
                'text' => $extension['description'],
                'author_name' => $extension['title'],
                'author_link' => 'https://typo3.org/extensions/repository/view/' . $extensionKey,
                'author_icon' => GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $extension['siteRelPath'] . $extension['ext_icon']
            ])->send('neue Extension Installiert');
        } catch (\Exception $e) {
            GeneralUtility::devLog($e->getMessage(), 't3_slack_examples', 3);
        }
    }
}