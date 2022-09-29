<?php
/* include autoloader */
require_once 'vendor/autoload.php';
define("DOMPDF_ENABLE_REMOTE", true);
//define("isHtml5ParserEnabled",true);

/* reference the Dompdf namespace */
use Dompdf\Dompdf;
/* instantiate and use the dompdf class */
$dompdf = new Dompdf();


$html = '
<html>
	<head>
		<style>
			@page { margin: 5px 10px; }
			#header { position: fixed; top: 15px;}
			#footer { position: fixed; left: 0px; bottom: 15px; right: 0px; height: 20px;}
			#footer .page:after { content: counter(page, upper-roman);}
		</style>
	</head>
	<body>
		<div id="header">
			<table width="100%">
				<tbody>
					<tr align="center">
						<td style="font-size:20px;">Fit4Kids</td>
					</tr>
				</tbody>
			</table>	
		</div>
		<div id="footer">
		</div>
		<div id="content" style="top:70px; left:10px; position:relative; color:red;">
			<p>
				1. Hvor mange personer skal jeres FitforKids Familie Kostplan dække?<br>
				A - Lorum
			</p>
			<p>
				2. Hvilke måltider skal jeres FitforKids FamilieKostplan dække?<br>
				A - Lorum
			</p>
			<p>
				3. Ønsker du at din «Min Nye Menu» Kostplan skal vise dit Hvilestofskifte?<br>
				A - Lorum
			</p>
			<p>
				4. Du kan ændre din vægt og højde her (husk at trykke "gem")<br>
				A - Lorum
			</p>
			<p>
				5. Hvad vil du gerne spise til morgenmad på en hverdag?<br>
				A - Lorum
			</p>
		</div>
	</body>
</html>';
// echo $html;
// exit;

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();
?>