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

		echo '<a id="wiki_api_link" style="display:none;" href="'.QA_WIKIAPI_ENDPOINT.'" data-srsearch="'. implode(' OR ', $GLOBALS['plugin_tag_desc_list']).'">API</a>';

		echo '<h2 id="wiki_widget_foundquestions_title" style="display:none; margin-top:0; padding-top:0;">Articles liés</h2>';
		echo '<h2 id="wiki_widget_noquestions_title" style="display:none; margin-top:0; padding-top:0;">Pas d\'articles</h2>';
		echo '<ul id="wiki_widget_ul" class="qa-related-q-list"></ul>';
	}

	/**
	 * Init default values for the plugin options
	 */
	function option_default($option)
	{
		if ($option == 'plugin_tag_desc_max_len')
			return 250;

		return null;
	}

	/**
	 * Defines the admin options form for the plugin
	 */
	function admin_form(&$qa_content)
	{
		require_once QA_INCLUDE_DIR.'app/admin.php';
		require_once QA_INCLUDE_DIR.'app/options.php';

		$permitoptions = qa_admin_permit_options(QA_PERMIT_USERS, QA_PERMIT_SUPERS, false, false);

		$saved=false;

		if (qa_clicked('plugin_tag_desc_save_button')) {
			qa_opt('plugin_tag_desc_max_len', (int)qa_post_text('plugin_tag_desc_ml_field'));
			$saved = true;
		}

		return array(
			'ok' => $saved ? 'Tag descriptions settings saved' : null,

			'fields' => array(
				array(
					'label' => 'Maximum length of tooltips:',
					'type' => 'number',
					'value' => (int)qa_opt('plugin_tag_desc_max_len'),
					'suffix' => 'characters',
					'tags' => 'NAME="plugin_tag_desc_ml_field"',
				),

			),

			'buttons' => array(
				array(
					'label' => 'Save Changes',
					'tags' => 'NAME="plugin_tag_desc_save_button"',
				),
			),
		);
	}
}
