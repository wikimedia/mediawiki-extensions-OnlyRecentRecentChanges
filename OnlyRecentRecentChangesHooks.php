<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\Html\FormOptions;
use MediaWiki\Preferences\Hook\GetPreferencesHook;
use MediaWiki\SpecialPage\Hook\ChangesListSpecialPageQueryHook;
use MediaWiki\User\Options\UserOptionsManager;
use Wikimedia\Rdbms\IConnectionProvider;

class OnlyRecentRecentChangesHooks implements
	ChangesListSpecialPageQueryHook,
	GetPreferencesHook
{
	private IConnectionProvider $dbProvider;
	private UserOptionsManager $userOptionsManager;

	public function __construct(
		IConnectionProvider $dbProvider,
		UserOptionsManager $userOptionsManager
	) {
		$this->dbProvider = $dbProvider;
		$this->userOptionsManager = $userOptionsManager;
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ChangesListSpecialPageQuery
	 * @param string $name name of the special page, e.g. 'Watchlist'
	 * @param array &$tables array of tables to be queried
	 * @param array &$fields array of columns to select
	 * @param array &$conds array of WHERE conditionals for query
	 * @param array &$query_options array of options for the database request
	 * @param array &$join_conds join conditions for the tables
	 * @param FormOptions $opts FormOptions for this request
	 */
	public function onChangesListSpecialPageQuery(
		$name,
		&$tables,
		&$fields,
		&$conds,
		&$query_options,
		&$join_conds,
		$opts
	) {
		if ( $this->userOptionsManager->getOption(
			RequestContext::getMain()->getUser(),
			'onlyrecentrecentchanges-show-only-recent-change' )
		) {
			$dbr = $this->dbProvider->getReplicaDatabase();

			if ( !in_array( 'page', $tables ) ) {
				array_unshift( $tables, 'page' );
			}

			$conds[] = $dbr->makeList(
				[
					'page_latest = rc_this_oldid',
					'rc_log_action != ' . $dbr->addQuotes( '' )
				],
				LIST_OR
			);

			$join_conds['page'] = [
				'LEFT JOIN',
				'page_latest = rc_this_oldid'
			];
		}
	}

	/**
	 * @param User $user
	 * @param array[] &$preferences
	 */
	public function onGetPreferences( $user, &$preferences ) {
		$preferences['onlyrecentrecentchanges-show-only-recent-change'] = [
			'section' => 'rc/advancedrc',
			'type' => 'toggle',
			'label-message' => 'onlyrecentrecentchanges-option'
		];
	}
}
