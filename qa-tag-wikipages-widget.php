<?php
/**
 * A widgets with a list of pages taken from the wiki
 */

class qa_tag_wikipages_widget
{
	/**
	 * Where to allow this widget
	 */
	function allow_template($template)
	{
		switch ($template) {
			case 'favorites': //  user’s ‘My Favorites’ page
			case 'hot': // hot questions page
			case 'qa': //  recent questions and answers (also home page if a custom home page is not being used)
			case 'question': // individual question pages
			case 'questions': // questions list
			case 'search': // search results page
			case 'tag': // recent questions for a tag
			case 'tags': // most popular tags
			case 'unanswered': // recent questions without answers
			case 'updates': // user’s ‘My Updates’ page
				return true;

			default:
				return false;
		}
	}

	function allow_region($region)
	{
		switch ($region) {
			case 'side': // the side panel, at least 160 pixels wide in Q2A’s built-in themes, with no height limit.
			case 'main': // the area showing the page’s main content, excluding the side panel. In the themes
						 // that come with Q2A, widgets in this region will have at least 728 pixels of width available,
						 // and no limit on height.
			case 'full': // the full width of the main area and side panel, at least 900 pixels wide in Q2A’s themes,
						  // with no height limit.New values for $region may be added in future, so you should return
						  // false for unrecognized values unless your widget is happy to be displayed in any area,
						  // no matter how narrow or wide.
				return true;

			default:
				return false;
		}
	}

	/**
	 * Outputs the widget in the tag page (shows the description on the right)
	 */
	function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
	{
		if (empty($GLOBALS['plugin_tag_desc_list']))
			return;

		$searchTerms = implode(' ', $GLOBALS['plugin_tag_desc_list']);
		$searchTerms = implode(' OR ', explode(' ', $searchTerms));
		echo '<a id="wiki_api_link" style="display:none;" href="'.QA_WIKIAPI_ENDPOINT.'" data-srsearch="'. $searchTerms .'">API</a>';

		echo '<h2 id="wiki_widget_foundquestions_title" style="display:none; margin-top:0; padding-top:0;">'.qa_lang_html('plugin_tag_desc/no_linked_articles_titles').'</h2>';
		echo '<h2 id="wiki_widget_noquestions_title" style="display:none; margin-top:0; padding-top:0;">'.qa_lang_html('plugin_tag_desc/no_articles_titles').'</h2>';
		echo '<ul id="wiki_widget_ul" class="qa-related-q-list"></ul>';
	}

}
