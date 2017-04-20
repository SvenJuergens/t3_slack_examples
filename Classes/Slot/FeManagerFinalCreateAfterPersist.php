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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class FeManagerFinalCreateAfterPersist
{

    /**
     * Send a Message , if a new User has completed the Registration
     *
     * @param $user \In2code\Femanager\Domain\Model\User;
     * @param $action
     * @param $parentObject \In2code\Femanager\Controller\AbstractController
     */
    public function feManagerNewUser($user, $action, $parentObject)
    {
        $client = GeneralUtility::makeInstance(T3Slack::class);
        $feManagerNewUser = LocalizationUtility::translate(
            'feManagerNewUser',
            't3_slack_examples',
            GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST')
        );
        try {
            $client->withIcon(':+1:')->attach([
                'fallback' => 'New User',
                'color' => 'good',
                'fields' => [
                    [
                        'title' => htmlspecialchars($user->getFirstName() . ' ' . $user->getLastName()),
                        'value' => $user->getEmail()
                    ]
                ]
            ])->send($feManagerNewUser);
        } catch (\Exception $e) {
            GeneralUtility::devLog($e->getMessage(), 't3_slack_examples', 3);
        }
    }
}