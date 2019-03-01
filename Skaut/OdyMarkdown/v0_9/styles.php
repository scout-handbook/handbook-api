<?php declare(strict_types=1);
const _API_EXEC = 1;

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

?>

.oddHeaderRight
{
	color: #7F7F7F;
	font-style: italic;
	padding-right: 1.35em;
	padding-top: -1.65em;
	text-align: right;
}

.QRheader
{
	float: right;
	height: 60px;
	margin-right: -1em;
	margin-top: -2em;
	width: 60px;
}

.oddFooterLeft
{
	font-style: italic;
	padding-bottom: 0.2em;
}

.oddFooterRight
{
	float: right;
	height: 19px;
	margin-bottom: 1.85em;
	margin-top: -1.85em;
}

.evenFooterLeft
{
	font-family: 'odymarathon';
	font-size: 2.0em;
	padding-bottom: 0.1em;
}

.evenFooterRight
{
	float: right;
	height: 15px;
	margin-bottom: 1.85em;
	margin-top: -1.85em;
}

.dottedline
{
	border-right: solid 250px white;
	margin-right: -250px;
	padding-right: -250px;
}

.dottedpage
{
	background-image: url('<?php echo($CONFIG->apiuri); ?>/internal/OdyMarkdown/dottedlines.png');
	background-repeat: no-repeat;
	background-size: cover;
	height: 1000px;
}

h1
{
	font-size: 1.7em;
	text-align: center;
}

h2
{
	font-size: 1.15em;
	padding-bottom: -0.6em;
}

h3
{
	font-size: 1em;
	padding-bottom: -0.5em;
}

body
{
	line-height: 160%;
	text-align: justify;
}

ul, ol
{
	padding-left: 3em;
	padding-top: 0.4em;
}

li
{
	padding-bottom: 0.3em;
	padding-top: -0.35em;
}

ul p, ol p
{
	padding-bottom: -1em;
	padding-top: -1em;
}

table
{
	border: 1px solid black;
	border-collapse: collapse;
	page-break-inside: avoid;
	width: 100%
}

th, td
{
	border: 1px solid black;
	padding: 0.3em 0.8em;
}

a
{
	color: black;
	text-decoration: none;
}
