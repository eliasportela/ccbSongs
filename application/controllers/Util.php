<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Util extends CI_Controller {

	public function IndexarCd() {

		require_once(APPPATH.'libraries/simple_html_dom.php');

		$cd_inicio = 0;
		$limit = 1000;

		$sql = "SELECT ref_cd FROM cd WHERE fg_ativo = 1 LIMIT $limit OFFSET $cd_inicio";
        $cds = $this->Crud_model->Query($sql);

		foreach ($cds as $cd) {

            $base = "http://www.canticosccb.com.br";

            $ref = $cd->ref_cd;
            $page = $base . "/cds/" . $ref;

            if ($html = file_get_html($page)) {

                echo "Indexando CD: " . $ref . "\n";

                $dados = [];
                $section = $html->find('dd');
                foreach ($section as $element) {
                    $dados[] = $element->plaintext;
                }

                $dataModel = array(
                    'qtd_canticos' => $dados[6]
                );

                $this->Crud_model->Update('cd', $dataModel, array("ref_cd" => $ref));

            } else {
                echo "CD: " . $ref . " não encontrado\n";
            }
        }
	}

	public function IndexarJson() {

	    $cd_inicio = 4718;
        $cd_fim = 5000;

        for ($i=$cd_inicio; $i <= $cd_fim; $i++) {

            //https seguro
            $base = "https://www.canticosccb.com.br";

            $page = "http://www.canticosccb.com.br/radios/" . $i . "/cd.json";

            $service_url = $page;
            $curl = curl_init($service_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            $curl_response = curl_exec($curl);
            curl_close($curl);

            $json_objekat = json_decode($curl_response);

            if ($json_objekat != NULL && !isset($json_objekat->status)) {
                $dados = $this->Crud_model->Read('cd',array('ref_cd' => $i));

                if (!$dados) {
                    echo "Indexando CD: " . $i . "\n";

                    $dados =  $json_objekat[0];

                    $title = $dados->cd->name;
                    $categoria = $dados->cd->category;
                    $singer = $dados->cd->singer;

                    if ($title == null || $categoria == null || $singer == null) {
                        continue;
                    }

                    $res = $this->Crud_model->Read('category', array('ref_category' => $categoria->name));
                    if ($res) {
                        $categoria = $res->id_category;

                    } else {
                        $categoria = $this->Crud_model->InsertId('category', array('name' => $categoria->name, 'ref_category' => $categoria->name));
                    }

                    $res = $this->Crud_model->Read('singer', array('ref_singer' => $singer->id));
                    if ($res) {
                        $singer = $res->id_singer;

                    } else {
                        $singer = $this->Crud_model->InsertId('singer', array('name' => $singer->name, 'ref_singer' => $singer->id));
                    }

                    $dataModel = array(
                        'title' => $title,
                        'ref_cd' => $i,
                        'id_singer' => $singer,
                        'id_category' => $categoria
                    );


                    $id_cd = $this->Crud_model->InsertId('cd', $dataModel);

                    if ($id_cd) {
                        foreach ($json_objekat as $hino) {

                            $dataModel = array(
                                'id_cd' => $id_cd,
                                'title' => $hino->name,
                                'url' => $base . $hino->song_file
                            );

                            $this->Crud_model->Insert('hymn', $dataModel);
                        }
                    }

                } else {
                    echo "CD: " . $i . " ja se encontra cadastrado\n";
                }

            } else {
                echo "CD: " . $i . " não encontrado\n";
            }

        }
    }

    public function DownloadAudioFiles() {

        $sql = "SELECT c.id_cd, c.title, h.id_hymn, h.url FROM cd c
                INNER JOIN hymn h ON (c.id_cd = h.id_cd)
                where qtd_canticos > 40000 and h.url like 'http%'
                order by qtd_canticos asc limit 10";

        $cds = $this->Crud_model->Query($sql);

        echo "=====================================\n";
        echo "Baixando cd: " . $cds[0]->title . "\n";
        foreach ($cds as $hymn) {

            echo "Baixando hino:" . $hymn->id_hymn . "\n";

            $folderPath = 'assets/files/'.$hymn->id_cd;
            if (!file_exists($folderPath)) {
                mkdir($folderPath);
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $hymn->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $data = curl_exec($ch);
            curl_close($ch);

            $filename = $folderPath . '/' . $hymn->id_cd . '_' . $hymn->id_hymn . ".mp3";
            $fp = fopen($filename, 'w');
            if ($fp != false){
                fwrite($fp, $data);
                fclose($fp);

                $dataModel = array(
                    'url' => $filename
                );
                $this->Crud_model->Update('hymn', $dataModel, array("id_hymn" => $hymn->id_hymn));
            }

        }
    }
}