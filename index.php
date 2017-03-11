<?php namespace fhall\Resume; ?>
<!DOCTYPE html>
<?php
	/** Require composer autoloader */
	require __DIR__ . '/vendor/autoload.php';

	/** URL for the Markdown transformer service */
	$markdown_transformer_service_url = 'https://md-transformer.herokuapp.com/';

	/** Names and URLs to the markdown resources that are to be transformed */
	$markdown_resources_urls = [
		'bio' => 'https://raw.githubusercontent.com/fhall/resume/master/content/bio.md',
		'experience' => 'https://raw.githubusercontent.com/fhall/resume/master/content/experience.md',
		'education' => 'https://raw.githubusercontent.com/fhall/resume/master/content/education.md',
		'contact' => 'https://raw.githubusercontent.com/fhall/resume/master/content/contact.md'
	];

	/** The transformed content (initially an empty array) */
	$content = [];

	/** Loop throught the markdown resources urls, transform the content and populate the content array */
	$http_client = new \GuzzleHttp\Client(['base_uri' => $markdown_transformer_service_url]);
	foreach ($markdown_resources_urls as $name => $url) {
		$http_response = $http_client->request('POST', '', ['body' => $url]);
		$content[$name] = $http_response->getBody();
	}

?>
<html>
<head>
	<title>fhall</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Slabo+27px" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<div id="wrapper">

<header>
	<h1>Resume of Fredrik Hall</h1>
</header>

<nav>
<?php foreach($content as $name => $html) : ?>
	<a href="#<?php echo $name; ?>"><?php echo $name; ?></a>
<?php endforeach; ?>
</nav>

<section id="content">

<?php
	/** Loop through the transformed content array and put each content resource in a separate section */
	foreach ($content as $name => $html) :
?>
	<section id="<?php echo $name; ?>">
		<?php echo $html; ?>
	</section>
<?php endforeach; ?>

</section>

</div>

</body>
</html>
