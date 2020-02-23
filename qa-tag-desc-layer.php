<?php

class qa_html_theme_layer extends qa_html_theme_base
{
	function post_tag_item($taghtml, $class)
	{
		require_once QA_INCLUDE_DIR.'util/string.php';

		// Load all tags descriptions in a massive GLOBAL, cached array
		if (!isset($GLOBALS['plugin_tag_desc_map']))
		{
			$GLOBALS['plugin_tag_desc_map'] = array();

			$result=qa_db_query_sub(
				'SELECT tag, content FROM ^tagmetas WHERE tag IN ($) AND title="description"',
				array_keys($GLOBALS['plugin_tag_desc_list'])
			);

			$GLOBALS['plugin_tag_desc_map'] = qa_db_read_all_assoc($result, 'tag', 'content');
		}

		if (!empty($GLOBALS['plugin_tag_desc_map']))
		{
			// Now actually make the replacement in the tag TITLE attribute
			if (preg_match('/,TAG_DESC,([^,]*),/', $taghtml, $matches))
			{
				$taglc = $matches[1]; // the tag name (lower case)

				if (isset($GLOBALS['plugin_tag_desc_map'][$taglc]))
				{
					$description = $GLOBALS['plugin_tag_desc_map'][$taglc];
					$description = qa_shorten_string_line($description, qa_opt('plugin_tag_desc_max_len'));
					$taghtml = str_replace($matches[0], qa_html($description), $taghtml);
				}
				else
					$taghtml = str_replace($matches[0], '', $taghtml);
			}
		}

		qa_html_theme_base::post_tag_item($taghtml, $class);
	}

}