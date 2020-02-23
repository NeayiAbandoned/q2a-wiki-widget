<?php

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

		$description = qa_db_tagmeta_get($tag, 'description');
		$editurlhtml = qa_path_html('tag-edit/'.$tag);

		$allowediting = !qa_user_permit_error('plugin_tag_desc_permit_edit');

		if (strlen($description))
		{
			echo '<span class="tag-description">';
			echo qa_html($description);
			echo '</span>';
			if ($allowediting)
				echo ' - <a href="'.$editurlhtml.'">'.qa_lang_html('plugin_tag_desc/edit_tag_link').'</a>';

		} elseif ($allowediting)
			echo '<a href="'.$editurlhtml.'">'.qa_lang_html('plugin_tag_desc/create_desc_link').'</a>';
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
