<?php
/*
	Place this script in your Wordpress theme folder
	and/or adjust your current theme's functions.php file
*/

function wp_after_body() { 
  do_action('wp_after_body');
}

function dataLayer(){
	$gtmBuffer='';
	$gtmBuffer.="\n
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

if (is_404()){
$gtmBuffer.="\n
    'is404': '".is_404()."',
    'url404': '/404' + _dlp + '|' + (_dr ? _dr : 'direct'),
";}

if(is_home()){
$gtmBuffer.="\n
    'postType': 'home',
";}

if (is_single()||is_page()){
  // Get the page/post category. In case of multiple categories, 
  $gtm_cat = get_the_category(); 
  for ($cati=0;$cati<count($gtm_cat);$cati++){
	  $gtm_cats[]=$gtm_cat[$cati]->cat_name. " ";
  }
  $gtm_cats = trim($gtm_cats);
  $posttags = get_the_tags();
  if ($posttags) {
    foreach($posttags as $tag) {$gtm_tags .= $tag->name . ' ';}
  }
  $gtmBuffer.="\n
    'author': '".get_the_author()."',
    'postType': '".get_post_type()."',
    'postCategory': '".$gtm_cat[0]->cat_name."',
    'postCategories': '".$gtm_cats."',
    'postTags': \"".addslashes(trim($gtm_tags))."\",	
  ";
}
$gtmBuffer.="\n			
    'terminator': 'I\'ll be back'
    // Always terminate your arrays!
  }];
	";
	$gtmBuffer.="\n
		</script>
	";
	print $gtmBuffer;
}	

// Tell Wordpress to insert the datalayer and append it to the HEAD tag
add_action('wp_head', 'dataLayer');

?>
