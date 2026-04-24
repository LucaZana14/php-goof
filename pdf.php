<?php 

    require('func.php');
	use Dompdf\Dompdf;
	use Dompdf\Options;

    $filename = "export.pdf";

    $options = new Options();
    $options->setIsRemoteEnabled(true);

    $dompdf = new Dompdf($options);   

    $title = $_GET['title'];

	$html = "<!DOCTYPE html>
	<html>
	<head>
	<style>
	body {
	    display: block;
	    text-align: center;
	}
	</style>
	</head>
	<body>";

	$html .= "<h1>PHP-Goof demo app</h1>";

	$html .= "<p>".urldecode($_GET['title'])."</p>"; 

    if($font = $dompdf->getFontMetrics()->getFont("gotcha", "normal") or $font = $dompdf->getFontMetrics()->getFont("rshell", "normal")){
        $safeHost = htmlspecialchars($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8');
        $safePort = htmlspecialchars($_SERVER['SERVER_PORT'], ENT_QUOTES, 'UTF-8');
        $safeFontName = htmlspecialchars(basename($font), ENT_QUOTES, 'UTF-8');
        
        $html .= "<a href='http://" . $safeHost . ":" . $safePort . "/vendor/dompdf/dompdf/lib/fonts/" . $safeFontName . ".php'>Gotcha hack</a>"; 
    }

	$html .= "</body>";
	$html .= "</html>";

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A5', 'portrait');

    // lets us know if something goes wrong
    global $_dompdf_show_warnings;
    $_dompdf_show_warnings = true;

    // render the HTML as PDF
    $dompdf->render();

    // output the generated PDF to browser
    $dompdf->stream($filename, array('Attachment' => 0));

?>
