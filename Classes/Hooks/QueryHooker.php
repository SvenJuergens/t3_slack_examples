<?php
namespace SvenJuergens\T3SlackExamples\Hooks;

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
use TYPO3\CMS\Core\Database\PostProcessQueryHookInterface;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class QueryHooker implements PostProcessQueryHookInterface
{
    /**
     * Post-processor for the SELECTquery method.
     *
     * @param string $select_fields Fields to be selected
     * @param string $from_table Table to select data from
     * @param string $where_clause Where clause
     * @param string $groupBy Group by statement
     * @param string $orderBy Order by statement
     * @param int $limit Database return limit
     * @param \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
     * @return void
     */
    public function exec_SELECTquery_postProcessAction(&$select_fields, &$from_table, &$where_clause, &$groupBy, &$orderBy, &$limit, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject)
    {}

    /**
     * Post-processor for the exec_INSERTquery method.
     *
     * @param string $table Database table name
     * @param array $fieldsValues Field values as key => value pairs
     * @param string|array $noQuoteFields List/array of keys NOT to quote
     * @param \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
     * @return void
     */
    public function exec_INSERTquery_postProcessAction(&$table, array &$fieldsValues, &$noQuoteFields, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject)
    {
        if ($table !== 'sys_log') {
            return;
        }
        if ($fieldsValues['error']  != "2") {
            return;
        }
        $newSysLogEntry =
            LocalizationUtility::translate(
                'newSysLogEntry',
                't3_slack_examples',
                [0 => GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST')]
            );
           $client = GeneralUtility::makeInstance(T3Slack::class);
           $client->withIcon(':flushed:')->attach([
               'fallback' => 'System Error',
               'color' => 'danger',
               'fields' => [
                   [
                       'title' => 'System Error',
                       'value' => $fieldsValues['details']
                   ]
               ]
           ])->send($newSysLogEntry);
    }

    /**
     * Post-processor for the exec_INSERTmultipleRows method.
     *
     * @param string $table Database table name
     * @param array $fields Field names
     * @param array $rows Table rows
     * @param string|array $noQuoteFields List/array of keys NOT to quote
     * @param \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
     * @return void
     */
    public function exec_INSERTmultipleRows_postProcessAction(&$table, array &$fields, array &$rows, &$noQuoteFields, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject)
    {}

    /**
     * Post-processor for the exec_UPDATEquery method.
     *
     * @param string $table Database table name
     * @param string $where WHERE clause
     * @param array $fieldsValues Field values as key => value pairs
     * @param string|array $noQuoteFields List/array of keys NOT to quote
     * @param \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
     * @return void
     */
    public function exec_UPDATEquery_postProcessAction(&$table, &$where, array &$fieldsValues, &$noQuoteFields, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject)
    {
        if($table !== 'tx_scheduler_task'){
            return;
        }
        if($fieldsValues['lastexecution_failure'] == ''){
            return;
        }

        $client = GeneralUtility::makeInstance(T3Slack::class);
        $tmp = unserialize($fieldsValues['lastexecution_failure']);
        $client->attach([
            'fallback' => 'Scheduler Error',
            'text' => 'Scheduler Error',
            'color' => 'danger',
            'fields' => [
                [
                    'title' => $tmp['code'],
                    'value' => $tmp['message']
                ]
            ]
        ])->send('A Scheduler task returned an error');

    }

    /**
     * Post-processor for the exec_DELETEquery method.
     *
     * @param string $table Database table name
     * @param string $where WHERE clause
     * @param \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
     * @return void
     */
    public function exec_DELETEquery_postProcessAction(&$table, &$where, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject)
    {}

    /**
     * Post-processor for the exec_TRUNCATEquery method.
     *
     * @param string $table Database table name
     * @param \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject
     * @return void
     */
    public function exec_TRUNCATEquery_postProcessAction(&$table, \TYPO3\CMS\Core\Database\DatabaseConnection $parentObject)
    {}
}