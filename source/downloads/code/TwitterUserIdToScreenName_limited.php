<?php
/**
 * Getting the Twitter usename from Twitter ID
 * This approach does not work in large scale. It's limited to 150 requests per hour.
 *
 * @author Massoud Seifi, Ph.D. @ MetaDataScience.com
 */

class TwitterUserIdToScreenName {

	protected $href_base = 'http://twitter.com/users/show/'; 

	public function getScreenNameFromUserId($twitter_user_id){

		if (!is_numeric($twitter_user_id)){
		    return false;
		}

		$href = $this->href_base . $twitter_user_id . '?format=json';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $href); 
		curl_setopt($ch, CURLOPT_HEADER         , false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION , true);

		$result = curl_exec($ch);
		curl_close($ch); 

		$json = json_decode($result, true);
		
		if (isset($json['screen_name'])){
			return $json['screen_name'];
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
