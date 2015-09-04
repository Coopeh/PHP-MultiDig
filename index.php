<?php

/*
* Dig for DNS Records
*
* Ed Cooper 2015
*/

date_default_timezone_set('Europe/London');
ini_set('max_execution_time', 300);
ini_set("display_errors", 0);

?>
<html>
	<head>
		<title>PHP MultiDig</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="favicon.ico" />
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
		<link href="assets/css/style.min.css" rel="stylesheet" type="text/css">
		<script src="assets/js/jquery.min.js" type="text/javascript"></script>
		<script src="assets/js/ZeroClipboard.min.js" type="text/javascript"></script>
		<script src="assets/js/script.min.js" type="text/javascript"></script>
		<script type="text/javascript">
		var _paq = _paq || [];
		_paq.push(['trackPageView']);
		_paq.push(['enableLinkTracking']);
		(function() {
			var u="//stats.ed.gs/";
			_paq.push(['setTrackerUrl', u+'piwik.php']);
			_paq.push(['setSiteId', 151]);
			var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
			g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
		})();
		</script>
		<noscript><p><img src="//stats.ed.gs/piwik.php?idsite=151" style="border:0;" alt="" /></p></noscript>
	</head>
	<body>
		<div class="github-fork-ribbon-wrapper right">
				<div class="github-fork-ribbon">
						<a href="https://github.com/Coopeh/PHP-MultiDig/">Fork me on GitHub</a>
				</div>
		</div>
		<div class="container">
			<div class="header">
				PHP MultiDig
			</div>
			<div class="form">
				<form name="dig" id="dig" action="process.php" method="post">
					<div class="textarea">
						<textarea rows="10" name="domains" id="domains" placeholder="List of domains" tabindex="1" autofocus/></textarea>
					</div>
					<div class="radio_list">
						<ul>
							<li>
								<input type="radio" name="radio" id="radio1" value="radio1" class="radio" checked/>
								<label for="radio1">A Records</label>
							</li>

							<li>
								<input type="radio" name="radio" id="radio2" value="radio2" class="radio"/>
								<label for="radio2">AAAA Records</label>
							</li>

							<li>
								<input type="radio" name="radio" id="radio3" value="radio3" class="radio"/>
								<label for="radio3">CNAME Records</label>
							</li>

							<li>
								<input type="radio" name="radio" id="radio4" value="radio4" class="radio"/>
								<label for="radio4">MX Records</label>
							</li>

							<li>
								<input type="radio" name="radio" id="radio5" value="radio5" class="radio"/>
								<label for="radio5">NS Records</label>
							</li>

							<li>
								<input type="radio" name="radio" id="radio6" value="radio6" class="radio"/>
								<label for="radio6">PTR Records</label>
							</li>

							<li>
								<input type="radio" name="radio" id="radio7" value="radio7" class="radio"/>
								<label for="radio7">SRV Records</label>
							</li>

							<li>
								<input type="radio" name="radio" id="radio8" value="radio8" class="radio"/>
								<label for="radio8">TXT Records</label>
							</li>

							<li>
								<input type="radio" name="radio" id="radio9" value="radio9" class="radio"/>
								<label for="radio9">ALL Records</label>
							</li>

							<li>
								<input type="radio" name="radio" id="radio10" value="radio10" class="radio"/>
								<label for="radio10">Reverse DNS</label>
							</li>
						</ul>
					</li>
					</div>
					<div class="submit">
						<button class="submit" type="submit" tabindex="2">Submit</button>
						<button class="clear" type="button" tabindex="3">Clear</button>
					</div>
				</form>
			</div>
			<div class="results">
				<div class="content" id="content">
					<table id="content-list"></table>
				</div>
			</div>
			<button class="copy-button" id="copy-button">Copy To Clipboard</button>
			<a class="export-button" id="export-button">Export To CSV</a>
			<button class="sort-button" id="sort-button">Sort By Name</button>
			<button class="hide-button" id="hide-button">Hide Recordless Domains</button>
			<div class="footer">
				<a href="https://ed.gs" target="_blank">Ed Cooper <?php echo date("Y"); ?></a>
			</div>
		</div>
	</body>
</html>
