<?php 
require_once('simple_html_dom.php');

$cd_inicio = 3000;
$cd_fim = 3005;

for ($i=$cd_inicio; $i <= $cd_fim; $i++) {

	$page = 'https://www.canticosccb.com.br/cds/'.$i;
	
	if ($html = file_get_html($page)) {	
		echo "CD: " . $i;
		echo "<br>";
		echo "Titulo: " . $html->find('.titulo',0)->plaintext;
		echo "<br>";
		$section = $html->find('dd'); 
		foreach($section as $element) {
			$texto = $element->plaintext;

			if ($texto != "") {
				echo $texto;
				echo "<br>";
			}
		}
		echo "<br>";

	} else {
		echo "CD: ".$i." não encontrado";
		echo "<br>";
	}

}

?>