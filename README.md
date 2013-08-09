### MediaWiki extension OnlyRecentRecentChanges

The behaviour of the recent changes view is changed so that any changed article is only listed once, older changes are not listed any longer.

### Manual and homepage of the extension
http://www.mediawiki.org/wiki/Extension:OnlyRecentRecentChanges

### Installation

```
cd $IP/extensions
git clone https://github.com/Wikinaut/MediaWiki-extension-OnlyRecentRecentChanges.git OnlyRecentRecentChanges
```

add the following line to your LocalSettings.php:

```
require_once( "$IP/extensions/OnlyRecentRecentChanges/OnlyRecentRecentChanges.php" );
```

### known bugs
The current version also suppresses log events (delete, upload ...) because I don't know how to avoid this unwanted side effect.

### reporting bugs or improvements
use the issue tracker https://github.com/Wikinaut/MediaWiki-extension-OnlyRecentRecentChanges/issues
