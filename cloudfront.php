<?php

require_once(dirname( __FILE__ ).'/aws/aws-autoloader.php');
use Aws\CloudFront\CloudFrontClient;

function create_cf_distro($origin,$cname){	
	// Instantiate the S3 client with your AWS credentials and desired AWS region
	$client = CloudFrontClient::factory(array(
		'key'    => AWS_ACCESS_KEY,
		'secret' => AWS_SECRET_KEY,
	));
	
	$result = $client->createDistribution(array(
		'Aliases' => array('Quantity' => 1, 'Items' => array($cname)),
		'CacheBehaviors' => array('Quantity' => 0),
		'Comment' => 'InstaSite Signup',
		'Enabled' => true,
		'CallerReference' => 'InstaSites-'.$origin,
		'DefaultCacheBehavior' => array(
			'MinTTL' => 3600,
			'ViewerProtocolPolicy' => 'allow-all',
			'TargetOriginId' => 'InstaSites-'.$origin,
			'TrustedSigners' => array(
				'Enabled'  => true,
				'Quantity' => 1,
				'Items'    => array('self')
			),
			'ForwardedValues' => array(
				'QueryString' => true,
				'Cookies' => array(
					'Forward' => 'none'
				)
			),
			'TrustedSigners' => array(
				'Enabled' => false,
				'Quantity' => 0
			)
		),
		'DefaultRootObject' => '',
		'Logging' => array(
			'Enabled' => false,
			'Bucket' => '',
			'Prefix' => '',
			'IncludeCookies' => true,
		),
		'Origins' => array(
			'Quantity' => 1,
			'Items' => array(
				array(
					'Id' => 'InstaSites-'.$origin,
					'DomainName' => $origin,
					'CustomOriginConfig' => array(
						// HTTPPort is required
						'HTTPPort' => 80,
						// HTTPSPort is required
						'HTTPSPort' => 443,
						// OriginProtocolPolicy is required
						'OriginProtocolPolicy' => 'http-only',
					)
				)
			)
		),
		'PriceClass' => 'PriceClass_All',
	));
	
	//printf('%s - %s - %s', $result['Status'], $result['Location'], $result['DomainName']) . "\n";
	if($result['Status']=="InProgress"){
		return $result['DomainName'];
	}
	return false;
}
?>