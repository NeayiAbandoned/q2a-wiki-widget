<?php

class qa_html_theme_layer extends qa_html_theme_base
{
	function post_tag_item($taghtml, $class)
	{
		require_once QA_INCLUDE_DIR.'util/string.php';

		$tag_lcase = '';
		$titleAttribute = '';

		if (preg_match('/,TAG_DESC,([^,]*),/', $taghtml, $matches))
		{
			$tag_lcase = $matches[1]; // the tag name (lower case)
			$titleAttribute = $matches[0];
		}

		if (!isset($GLOBALS['plugin_tag_page_tags']))
			$GLOBALS['plugin_tag_page_tags'] = array();

		$GLOBALS['plugin_tag_page_tags'][] = $tag_lcase;

		// Load all tags descriptions in a GLOBAL, cached array
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
			if (!empty(tag_lcase))
			{
				if (isset($GLOBALS['plugin_tag_desc_map'][$tag_lcase]))
				{
					$description = $GLOBALS['plugin_tag_desc_map'][$tag_lcase];
					$description = qa_shorten_string_line($description, qa_opt('plugin_tag_desc_max_len'));
					$taghtml = str_replace($titleAttribute, qa_html($description), $taghtml);
				}
				else
					$taghtml = str_replace($titleAttribute, '', $taghtml);
			}
		}

		qa_html_theme_base::post_tag_item($taghtml, $class);
	}

}