<?php
/**
 * Getting the Twitter usename from Twitter ID
 * This code works fine in large scale.
 *
 * @author Massoud Seifi, Ph.D. @ MetaDataScience.com
 */

class TwitterUserIdToScreenName {

	protected $href_base = 'http://twitter.com/account/redirect_by_id?id=';

	public function getScreenNameFromUserId($twitter_user_id)
	{

		if (!is_numeric($twitter_user_id)){
		    return false;
		}

		$href = $this->href_base . $twitter_user_id;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $href); 
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_HEADER         , true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION , true);

		$result = curl_exec($ch);
		$info =  curl_getinfo($ch);
		curl_close($ch); 

		if (isset($info['url'])){
			return  str_replace( '/', '', parse_url($info['url'], PHP_URL_PATH));
		}

	}

}

$p = new TwitterUserIdToScreenName();

while ($line = fgets(STDIN)){
	$id = trim($line);
	$screen_name = $p->getScreenNameFromUserId($id);
	if ( is_string($screen_name) ){
		echo "$id,$screen_name\n";
	}
}
