<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Util extends CI_Controller {

	public function Indexar(){

		require_once(APPPATH.'libraries/simple_html_dom.php');

		$cd_inicio = 3101;
		$cd_fim = 4000;

		for ($i=$cd_inicio; $i <= $cd_fim; $i++) {

            $base = "http://www.canticosccb.com.br";
			$page = $base . "/cds/" . $i . "/songs";
			
	        $service_url = $page;
	        $curl = curl_init($service_url);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_POST, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	        $curl_response   = curl_exec($curl);
	        curl_close($curl);

	        $json_objekat = json_decode($curl_response);
			
			if ($json_objekat != NULL) {

                $page = $base . "/cds/".$i;
                if ($html = file_get_html($page)) {

                    echo "Indexando CD: ".$i."\n";

                    $cd_titulo = $html->find('.titulo',0)->plaintext;
                    $dados = [];
                    $section = $html->find('dd');
                    foreach($section as $element) {
                        $texto = $element->plaintext;
                        if ($texto != "") {
                            $dados[] = $texto;
                        }
                    }

                    $dataModel = array(
                        'title' => $cd_titulo,
                        'ref_cd' => $i,
                        'singer' => $dados[0],
                        'category' => $dados[1],
                        'volume' => $dados[2]
                    );

                    $id_cd = $this->Crud_model->InsertId('cd',$dataModel);

                    if ($id_cd) {
                        foreach ($json_objekat as $dado) {
                            $dataModel = array(
                                'id_cd' => $id_cd,
                                'title' => $dado->title,
                                'url' => $base . $dado->url
                            );

                            $this->Crud_model->Insert('hymn',$dataModel);
                        }
                    }


                }

            } else {
                echo "CD: ".$i." não encontrado\n";
            }
		}
	}
}