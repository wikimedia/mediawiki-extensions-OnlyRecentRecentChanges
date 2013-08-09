<?php
#
# OnlyRecentRecentChanges extension for MediaWiki
# http://www.mediawiki.org/wiki/Extension:OnlyRecentRecentChanges
#
# Installation:
# create a file $IP/extensions/OnlyRecentRecentChanges.php with this code
# add the following to LocalSettings.php:
# require_once( "extensions/OnlyRecentRecentChanges.php" );

if( !defined( 'MEDIAWIKI' ) ) {
	die( "This is not a valid entry point.\n" );
}

// Extension credits that will show up on Special:Version
$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'OnlyRecentRecentChanges',
	'descriptionmsg' => 'onlyrecentrecentchanges-desc',
	'version' => '1.2.0',
	'author' => 'Thomas Gries',
	'url' => 'http://www.mediawiki.org/wiki/Extension:OnlyRecentRecentChanges',
);

$dir = dirname( __FILE__ );
$wgExtensionMessagesFiles['onlyrecentrecentchanges'] = $dir . '/OnlyRecentRecentChanges.i18n.php';
$wgHooks['GetPreferences'][] = 'onGetPreferences';
$wgHooks['SpecialRecentChangesQuery'][] = 'onSpecialRecentChangesQuery';


// see http://www.mediawiki.org/wiki/Manual:Hooks/SpecialRecentChangesQuery
function onSpecialRecentChangesQuery( &$conds, &$tables, &$join_conds, $opts, &$query_options, &$select  ) {
	global $wgUser;

	if ( $wgUser->getOption( 'onlyrecentrecentchanges-tog' ) ) {
		if ( !in_array( 'page', $tables) ) array_unshift( $tables, 'page' );
		$conds[] = 'page_latest=rc_this_oldid';
	}
	return true;
}

/**
 * @param $user User
 * @param $preferences array
 * @return bool
 */
function onGetPreferences( $user, &$preferences ) {
	$preferences['onlyrecentrecentchanges-tog'] = array(
		'section' => 'rc/advancedrc',
		'type' => 'toggle',
		'label-message' => 'onlyrecentrecentchanges-option'
	);
	return true;
}

