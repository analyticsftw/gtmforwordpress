<?php
/*
	GTM Data Layer for Wordpress
	by Julien Coquet
	This function outputs a Google Tag Manager dataLayer JavaScript collection
	within the HEAD HTML tag. You can then point to the variables within the 
	collection using Google Tag Manager to power variables (macros) and triggers (rules).
	
	Place this script in your Wordpress theme folder and/or adjust your current theme's functions.php file
	
	For more details on Google Tag Manager, visit the Developer Reference at
	https://developers.google.com/tag-manager/quickstart
	
	You can extend this function by leveraging other contextual Wordpress elements and functions.
	Refer to the Wordpress Codex (knowledge base) at
	https://codex.wordpress.org/Developer_Documentation
*/

function dataLayer()
{
	$gtmBuffer = ''; // initialize GTM dataLayer output
	$gtmBuffer.= "\n
<!-- Google Tag Manager Data Layer -->
<script type='text/javascript'>

  // URL JavaScript toolbox, saves times creating variables
  var _d = document;
  var _dl = _d.location;
  var _dlp = _dl.pathname;
  var _dls = _dl.search;
  var _dr = _d.referrer;
  var dataLayer = [{
";
	if (is_404()) { // Error page management
		$gtmBuffer.= "\n
    'is404': '" . is_404() . "', // Is this a 404 page? (boolean)
    'url404': '/404' + _dlp + '|' + (_dr ? _dr : 'direct'), // If 404, create virtual URL with referrer
";
	}

	if (is_home()) { // is this the home page
		$gtmBuffer.= "\n
    'postType': 'home',
";
	}

	if (is_single() || is_page()) { // in case of page or post
		// Get the page/post category. In case of multiple categories, create an array
		$gtm_cat = get_the_category();
		for ($cati = 0; $cati < count($gtm_cat); $cati++) {
			$gtm_cats[] = $gtm_cat[$cati]->cat_name;
		}

		// Walk through post tags
		$gtm_cats = trim($gtm_cats);	 
		$posttags = get_the_tags();
		if ($posttags) {
			foreach($posttags as $tag) {
				$gtm_tags.= $tag->name . ' ';
			}
		}

		$gtmBuffer.= "\n
    'author': '" . get_the_author() . "',
    'postType': '" . get_post_type() . "',
    'postCategory': '" . $gtm_cat[0]->cat_name . "',
    'postCategories': '".join(",",$gtm_cats)."',
    'postTags': '" . addslashes(trim($gtm_tags)) . "',";
	}
	$gtmBuffer.= "\n'terminator': 'I\'ll be back' // Always terminate your arrays!
  }];

</script>\n";
	print $gtmBuffer;
}

// Tell Wordpress to insert the datalayer and append it to the HEAD tag
add_action('wp_head', 'dataLayer');

?>
