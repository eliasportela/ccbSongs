<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Util extends CI_Controller {

	public function IndexarRefQtdCd($ref) {

        $base = "http://www.canticosccb.com.br";
        $page = $base . "/cds/" . $ref;

        if ($html = file_get_html($page)) {

            echo "Referencia de quantidades cd: " . $ref . "\n";

            $dados = [];
            $section = $html->find('dd');
            foreach ($section as $element) {
                $dados[] = $element->plaintext;
            }

            return $dados[6];

        } else {
            echo "CD: " . $ref . " não encontrado\n";
        }

        return null;

	}

	public function IndexarJson() {
        require_once(APPPATH.'libraries/simple_html_dom.php');

	    $cd_inicio = 1001;
        $cd_fim = 10000;

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
                        'id_category' => $categoria,
                        'qtd_canticos' => $this->IndexarRefQtdCd($i)
                    );


                    $id_cd = $this->Crud_model->InsertId('cd', $dataModel);

                    if ($id_cd) {
                        foreach ($json_objekat as $hino) {

                            $dataModel = array(
                                'id_cd' => $id_cd,
                                'title' => $hino->name,
                                'url' => $base . $hino->song_file,
                                //'url' => $this->DownloadAudio($i,$base . $hino->song_file, rand(10000000, 100000000))
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

    public function DownloadAudio($ref, $url, $idHino) {

	    $folderPath = 'assets/files/'.$ref;
        if (!file_exists($folderPath)) {
            mkdir($folderPath);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        $filename = $folderPath . '/' . $ref . '_' . $idHino . ".mp3";
        $fp = fopen($filename, 'w');
        if ($fp != false){
            fwrite($fp, $data);
            fclose($fp);

            return "/" . $filename;

        }

        return null;

    }
}