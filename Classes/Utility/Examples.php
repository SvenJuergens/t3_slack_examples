<?php
namespace SvenJuergens\T3SlackExamples\Utility;

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

class Examples
{

    /**
     * @param $user \In2code\Femanager\Domain\Model\User;
     * @param $action
     * @param $parentObject \In2code\Femanager\Controller\AbstractController
     */
    public function feManagerNewUser($user, $action, $parentObject)
    {
            $client = GeneralUtility::makeInstance(T3Slack::class);
            $client->withIcon(':+1:')->attach([
                'fallback' => 'New User',
                'color' => 'good',
                'fields' => [
                    [
                        'title' => htmlspecialchars($user->getFirstName() . ' ' . $user->getLastName()),
                        'value' => $user->getEmail()
                    ]
                ]
            ])->send('Es hat sich ein neuer auf ' . GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . ' User registriert.');
    }
}