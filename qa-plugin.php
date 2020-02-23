<?php

/*
	Plugin Name: Tag Description and linked wiki pages
	Plugin URI: https://github.com/neayi/q2a-wiki-widget
	Plugin Description: Adds tag meta data, tooltips and linked wiki pages to be shown
	Plugin Version: 1.0
	Plugin Date: 2020-02-15
	Plugin Author: Bertrand Gorge
	Plugin Author URI: http://neayi.com/
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.8
	Plugin Update Check URI:
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

// The widget (that only shows in the tag's page, with its description)
qa_register_plugin_module(
	'widget', // type of module
	'qa-tag-desc-widget.php', // PHP file containing module class
	'qa_tag_descriptions_widget', // module class name in that PHP file
	'Tag Descriptions' // human-readable name of module
);

// The widget (that only shows in the tag's page, with its description)
qa_register_plugin_module(
	'widget', // type of module
	'qa-tag-wikipages-widget.php', // PHP file containing module class
	'qa_tag_wikipages_widget', // module class name in that PHP file
	'Tag wiki Pages' // human-readable name of module
);


// The edition page for each tag
qa_register_plugin_module(
	'page', // type of module
	'qa-tag-desc-edit.php', // PHP file containing module class
	'qa_tag_descriptions_edit_page', // name of module class
	'Tag Description Edit Page' // human-readable name of module
);

qa_register_plugin_overrides('qa-tag-desc-overrides.php');

qa_register_plugin_layer(
	'qa-tag-desc-layer.php', // PHP file containing layer
	'Tag Description Layer' // human-readable name of layer
);

qa_register_plugin_phrases(
	'lang/qa-tag-desc-lang-*.php', // pattern for language files
	'plugin_tag_desc' // prefix to retrieve phrases
);