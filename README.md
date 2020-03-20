
# q2a-wiki-widget
This Q2A plugin adds two widgets and adds titles to tags:

* A widget that shows wiki stories in relation with the current Q2A question (uses a OR search on all the currently shown tags in the page)
* A widget that is only shown in the TAG page, with the snippet of the corresponding page in the wiki (looks up the page with exactly the same text as the tag)
* Also fetchs this text to show on hover on each tag (title attribute)

## To install

**On Q2A side**
* You'll need to add the plugin in the qa-plugin folder
* You'll need to add the URL to your wiki API endpoint in the qa-config.php file:
    `define('QA_WIKIAPI_ENDPOINT', 'https://www.mediawiki.org/w/api.php');`

**On the wiki side**
* You'll need [CirrusSearch](https://www.mediawiki.org/wiki/Extension:CirrusSearch) (ElasticSearch) as the widget performs a OR search on all tags, and this won't give you many results if you don't have ElasticSearch behind your wiki
* You'll also need [TextExtracts](https://www.mediawiki.org/wiki/Extension:TextExtracts) because we use that to show only the excerpt of the page
* Finaly, you will need to configure CORS, otherwise the browser won't like that the Q2A pages do XHR with the mediawiki pages (unless the two platforms are on the same domain). One way to do that is to [hack your apache conf files](https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS/Errors/CORSMissingAllowOrigin). Another is two use [wgCrossSiteAJAXdomains](https://www.mediawiki.org/wiki/Manual:$wgCrossSiteAJAXdomains).

## Limitations
This will assume that your wiki is using URLs of the form https://yourWiki.com/wiki/Main - this is probably not your setup. If this is the case, just change that in `js\wiki_api_client.js` - I know it's not super clean. Will add some configuration for that later.
