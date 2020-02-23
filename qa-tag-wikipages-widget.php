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
//		echo print_r($GLOBALS['plugin_tag_desc_map'], true);


		$apiURL = 'https://fr.wikipedia.org/w/api.php?action=query&list=search&srwhat=text&srsearch=c%C3%A9page%20OR%20muscaris';
		$apiURL = 'http://localhost/pratiques/api.php?action=query&list=search&srwhat=text&srsearch=c%C3%A9page%20OR%20muscaris';

		$wiki = "http://localhost/wiki/";

		$endPoint = "http://localhost/pratiques/api.php";

		foreach ($GLOBALS['plugin_tag_desc_map'] as $k => $description)
		{
			$params = [
				"action" => "query",
				"list" => "search",
				"srsearch" => $k,
				"format" => "json"
			];

			$url = $endPoint . "?" . http_build_query( $params );

			$ch = curl_init( $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			$output = curl_exec( $ch );
			curl_close( $ch );

			$result = json_decode( $output, true );

			foreach ($result['query']['search'] as $result)
			{
				echo '<a href="'.$wiki.$result['title'].'">'.$result['title'].'</a><br/>';
			}
		}

/*
{
    "query": {
        "searchinfo": {
            "totalhits": 1
        },
        "search": [
            {
                "ns": 0,
                "title": "Muscaris",
                "pageid": 7050,
                "size": 1574,
                "wordcount": 202,
                "snippet": "|Name=<span class='searchmatch'>Muscaris</span>\n|Description=<span class='searchmatch'>Muscaris</span> est un c\u00e9page blanc cr\u00e9\u00e9 en 1987 par le WBI Freiburg (Allemagne). Issu d\n",
                "timestamp": "2020-01-14T16:01:32Z"
            }
        ]
    },

*/


		// require_once QA_INCLUDE_DIR.'db/metas.php';

		// $parts = explode('/', $request);
		// $tag = $parts[1];

		// $description = qa_db_tagmeta_get($tag, 'description');
		// $editurlhtml = qa_path_html('tag-edit/'.$tag);

		// $allowediting = !qa_user_permit_error('plugin_tag_desc_permit_edit');

		// if (strlen($description))
		// {
		// 	echo '<span class="tag-description">';
		// 	echo qa_html($description);
		// 	echo '</span>';
		// 	if ($allowediting)
		// 		echo ' - <a href="'.$editurlhtml.'">'.qa_lang_html('plugin_tag_desc/edit_tag_link').'</a>';

		// } elseif ($allowediting)
		// 	echo '<a href="'.$editurlhtml.'">'.qa_lang_html('plugin_tag_desc/create_desc_link').'</a>';
	}

	/**
	 * Init default values for the plugin options
	 */
	function option_default($option)
	{
		if ($option == 'plugin_tag_desc_max_len')
			return 250;

		if ($option == 'plugin_tag_desc_permit_edit') {
			require_once QA_INCLUDE_DIR.'app/options.php';
			return QA_PERMIT_EXPERTS;
		}

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
			qa_opt('plugin_tag_desc_permit_edit', (int)qa_post_text('plugin_tag_desc_pe_field'));
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

				array(
					'label' => 'Allow editing:',
					'type' => 'select',
					'value' => @$permitoptions[qa_opt('plugin_tag_desc_permit_edit')],
					'options' => $permitoptions,
					'tags' => 'NAME="plugin_tag_desc_pe_field"',
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
