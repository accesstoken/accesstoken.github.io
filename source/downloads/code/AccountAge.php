<?php
/**
 * Estimating the Facebook account age by finding the creation date of the oldest post.
 * A valid Facebook Access Token with read_stream permission is required.
 *
 * @author Massoud Seifi, Ph.D. @ MetaDataScience.com
 */

class AccountAge
{

	public $baseUrl;

	function __construct()
	{
		$this->baseUrl = 'https://graph.facebook.com/';
	}

	/**
	 * Run a Facebook FQL query
	 * @param string $fql Facebook query 
	 * @param string $access_token Facebook Access Token
	 * @return array Return Facebook query result
	 */
	public function doFQLRequest($fql, $access_token)
	{
		$url = $this->baseUrl	. 'fql?q=' . urlencode($fql)
			. '&access_token=' . $access_token;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		$decodedResult = json_decode(curl_exec($ch), true);
		curl_close($ch);

		$result = array();
		if(isset($decodedResult['data']))
			$result = $decodedResult['data'];
		else
			throw new Exception("Facebook FQL Error. Please check if the access token is valid.\n");

		return $result;
	}

	/**
	 * Estimate the account age by finding the creation date of the oldest post
	 * @param string $access_token Facebook Access Token
	 * @return integer Return the Facebook account age in seconds
	 */
	public function getAccountAge($access_token)
	{
		$date = new \DateTime('now');
		$timestamp = $date->getTimestamp();
		echo "# Finding the oldest post may take several minutes to complete.\n";
		echo "# Please wait ";
		while (true){ // Loop until finding the oldest post
			echo ".";
			$fql = "SELECT created_time FROM stream WHERE source_id = me()"
				. " AND created_time < " . $timestamp
				. " ORDER BY created_time ASC LIMIT 5000";
			$result = $this->doFQLRequest($fql, $access_token);
			if (!isset($result[0]['created_time']))
				break;
			$timestamp = $result[0]['created_time'];
		}
		echo "\n";
		$age = $date->getTimestamp() - $timestamp;
		return $age;
	}


	/**
	 * Display the account age in a human readable format
	 * @param int $age Account age in seconds
	 */
	public function printAccountAge($age)
	{
		$years = floor($age / (365*24*60*60));
		$months = floor(($age - $years * 365*24*60*60) / (30*24*60*60));
		$days = floor(($age - $years * 365*24*60*60 - $months * 30*24*60*60) / (24*60*60));
		echo "\nAccount age: $years years, $months months, $days days\n";	
	}

}

$p = new AccountAge();
// You need an Access Token with a read_stream permission
$access_token = 'AAACEdEose0cBAOY7bB3A9m7s3U6hbuJvfECxuZBFRN6YjqPC2eZB5x8WrnK51Gl3WsdwYovmxdPZCKFyJKB5TuFhpxsDJpAZCe9y6eutyQZDZD';
$age = $p->getAccountAge($access_token);
$p->printAccountAge($age);