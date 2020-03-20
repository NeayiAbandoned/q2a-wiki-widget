<?php

/**
 * Adds a data-taglc attribute to the tag, in order for wiki_api_client.js to lookup for
 * a description from the wiki
 */
function qa_tag_html($tag, $microformats=false, $favorited=false)
{
	$taghtml = qa_tag_html_base($tag, $microformats, $favorited);

	require_once QA_INCLUDE_DIR.'util/string.php';

	$taglc = qa_strtolower($tag);

	// store the tag name in a map, for future reference in qa-tag-wikipage-widget.php
	$GLOBALS['plugin_tag_desc_list'][$taglc] = $taglc;

	$anglepos = strpos($taghtml, '>');
	if ($anglepos !== false)
		$taghtml = substr_replace($taghtml, ' data-taglc="'.$taglc.'" title=""', $anglepos, 0);

	return $taghtml;
}
