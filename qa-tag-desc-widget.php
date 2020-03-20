<?php
/**
 * A widget that shows a description for a given tag. Shown only on the tag page.
 * Looks for the description directly in the wiki.
 */

class qa_tag_descriptions_widget
{
	/**
	 * Only allow this widget on the tag page
	 */
	function allow_template($template)
	{
		return ($template=='tag');
	}

	function allow_region($region)
	{
		return true;
	}

	/**
	 * Outputs the widget in the tag page (shows the description on the right)
	 */
	function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
	{
		require_once QA_INCLUDE_DIR.'db/metas.php';

		$parts = explode('/', $request);
		$tag = $parts[1];

		echo '<h2>'.mb_convert_case($tag, MB_CASE_TITLE).'</h2>';
		echo '<a id="wiki_api_link_tagdescription" style="display:none;" href="'.QA_WIKIAPI_ENDPOINT.'" data-srsearch="'. $tag.'">API: '.QA_WIKIAPI_ENDPOINT.'</a>';

		$targetURL = str_replace('api.php', 'wiki/', QA_WIKIAPI_ENDPOINT) . $tag;
		echo '<p id="wiki_see_more_p" style="margin: 10px; text-align:right"><a id="wiki_see_more" target="_blank" href="'.$targetURL.'">'.qa_lang_html('plugin_tag_desc/see_more_tag_link').'</a></p>';
	}
}
