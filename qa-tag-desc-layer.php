<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    function body_footer()
    {
		// https://questions.dev.tripleperformance.fr/qa-plugin/q2a-wiki-widget/js/wiki_api_client.js
        $this->content['body_footer'] = '<script src="' . qa_html(QA_HTML_THEME_LAYER_URLTOROOT . 'js/wiki_api_client.js') . '" type="text/javascript"></script>';

        qa_html_theme_base::body_footer();
    }

}