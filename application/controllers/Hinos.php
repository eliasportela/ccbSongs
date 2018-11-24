<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Hinos extends CI_Controller {

	public function GetCdHinos(){

        //$chave = $this->uri->segment(4);
        //$nivel_acesso = 1;
        $acesso_aprovado = true; //$this->Crud_model->ValidarToken($chave, $nivel_acesso);

        if ($acesso_aprovado) {

            $par = $this->uri->segment(3);

            if ($par) {

                $sql = "SELECT id_cd, title, singer, category, volume, visualizacao FROM cd WHERE id_cd = $par";
                $cd = $this->Crud_model->Query($sql);

                if ($cd) {
                    $dataModel = array('visualizacao' => $cd[0]->visualizacao + 1);
                    $this->Crud_model->Update('cd',$dataModel,array('id_cd' => $par));

                    $sql = "SELECT id_hymn, title, url, visualizacao FROM hymn WHERE id_cd = $par";
                    $hinos = $this->Crud_model->Query($sql);

                    $json = array_merge(((array)$cd[0]), array('hinos' => $hinos));

                    echo json_encode($json, JSON_UNESCAPED_UNICODE);;
                    return;

                } else {
                    $this->output->set_status_header('204');
                    return;
                }
            }
        }

        $this->output->set_status_header('401');

	}

    public function GetCategoriasHinos(){

        //$chave = $this->uri->segment(4);
        //$nivel_acesso = 1;
        $acesso_aprovado = true; //$this->Crud_model->ValidarToken($chave, $nivel_acesso);

        if ($acesso_aprovado) {

            $sql = "SELECT category FROM cd WHERE fg_ativo = 1 GROUP BY category ";
            $categorias = $this->Crud_model->Query($sql);

            if ($categorias) {

                $json = [];
                foreach ($categorias as $c) {

                    $sql = "SELECT id_cd, title FROM cd WHERE category = '$c->category'";
                    $cds = $this->Crud_model->Query($sql);

                    $json[] = array_merge(((array)$c), array('cds' => $cds));
                }

                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                return;

            } else {
                $this->output->set_status_header('204');
                return;
            }

        }

        $this->output->set_status_header('401');

    }

    public function GetHino(){

        //$chave = $this->uri->segment(4);
        //$nivel_acesso = 1;
        $acesso_aprovado = true; //$this->Crud_model->ValidarToken($chave, $nivel_acesso);

        if ($acesso_aprovado) {

            $par = $this->uri->segment(3);

            if ($par) {

                $hymn = $this->Crud_model->Read('hymn',array('id_hymn' => $par));

                if ($hymn) {

                    $dataModel = array('visualizacao' => $hymn->visualizacao + 1);
                    $this->Crud_model->Update('hymn',$dataModel,array('id_hymn' => $par));

                    $hymn = array(
                        'id_hymn' => $hymn->id_hymn,
                        'title' => $hymn->title,
                        'url' => $hymn->url,
                        'visualizacao' => $hymn->visualizacao + 1
                    );

                    echo json_encode($hymn, JSON_UNESCAPED_UNICODE);;
                    return;

                } else {
                    $this->output->set_status_header('204');
                    return;
                }
            }
        }

        $this->output->set_status_header('401');

    }
}