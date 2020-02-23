<?php

/**
 * Adds a TAG_DESC,tagname title to the tag, in order for qa-tag-desc-layer.php to replace
 * it with the proper tag description.
 */
function qa_tag_html($tag, $microformats=false, $favorited=false)
{
	$taghtml = qa_tag_html_base($tag, $microformats, $favorited);

	require_once QA_INCLUDE_DIR.'util/string.php';

	$taglc = qa_strtolower($tag);

	// store the tag name in a map, for future reference in qa-tag-desc-layer.php
	$GLOBALS['plugin_tag_desc_list'][$taglc] = true;

	$anglepos = strpos($taghtml, '>');
	if ($anglepos !== false)
		$taghtml = substr_replace($taghtml, ' title=",TAG_DESC,'.$taglc.',"', $anglepos, 0);

	return $taghtml;
}
