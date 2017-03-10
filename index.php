<!DOCTYPE html>
<?php
	/** URL for the Markdown transformer service */
	$markdown_transformer_service_url = 'https://md-transformer.herokuapp.com/';

	/** Names and URLs to the markdown resources that are to be transformed */
	$resume_resource_base_markdown_url = 'https://raw.githubusercontent.com/fhall/resume/master/content/';
	$markdown_resources_urls = [
		'bio' => $resume_resource_base_markdown_url . 'bio.md',
		'experience' => $resume_resource_base_markdown_url . 'experience.md',
		'education' => $resume_resource_base_markdown_url . 'education.md'
	];

	/** The transformed content (initially an empty array) */
	$content = [];

	/** Options for the stream context  */
	$options = array(
		'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST'
		)
	);

	/** Loop throught the markdown resources urls and transform the content */
	foreach ($markdown_resources_urls as $name => $url) {
		$options['http']['content'] = $url; // Set the content to be sent
		$context  = stream_context_create($options); // Create a stream context
		$content[$name] = file_get_contents($markdown_transformer_service_url, false, $context); // Send the stream context and retrieve the response
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
	<h1></h1>
</header>

<section id="content">

<?php
	/** Loop through the transformed content and put each content resource in a separate section */
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
