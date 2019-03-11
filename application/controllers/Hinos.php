<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Hinos extends CI_Controller {

	public function GetCdHinos(){

        $acesso_aprovado = $this->Crud_model->ValidarToken($this->uri->segment(4), 1);

        if ($acesso_aprovado) {

            $par = $this->uri->segment(3);

            if ($par) {

                $sql = "SELECT c.id_cd, c.title, s.name as singer, ct.name as category FROM cd c INNER JOIN category ct ON (c.id_category = ct.id_category) INNER JOIN singer s ON (c.id_singer = s.id_singer) WHERE id_cd = $par";
                $cd = $this->Crud_model->Query($sql);

                if ($cd) {

                    $sql = "SELECT id_hymn, title, url FROM hymn WHERE id_cd = $par";
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

            //die(var_dump("rw"));

            $sql = "SELECT id_category, name as category FROM category WHERE fg_ativo = 1";
            $categorias = $this->Crud_model->Query($sql);

            if ($categorias) {

                $json = [];
                foreach ($categorias as $c) {

                    $sql = "SELECT id_cd, title FROM cd WHERE id_category = '$c->id_category' ORDER BY qtd_canticos DESC";
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

        $acesso_aprovado = $this->Crud_model->ValidarToken($this->uri->segment(4), 1);

        if ($acesso_aprovado) {

            $par = $this->uri->segment(3);

            if ($par) {

                $hymn = $this->Crud_model->Read('hymn',array('id_hymn' => $par));
                $favorito = $this->Crud_model->Read('favoritos_usuario', array('id_hymn' => $par, 'id_usuario' => $acesso_aprovado));

                if ($hymn) {

                    $this->Crud_model->Insert('historico_usuario', array('id_hymn' => $par,'id_usuario' => $acesso_aprovado));

                    $hymn = array(
                        'id_hymn' => $hymn->id_hymn,
                        'title' => $hymn->title,
                        'url' => $hymn->url,
                        'favorito' => ($favorito !== FALSE)
                    );

                    echo json_encode($hymn, JSON_UNESCAPED_UNICODE);
                    return;

                } else {
                    $this->output->set_status_header('204');
                    return;
                }
            }
        }

        $this->output->set_status_header('401');

    }

    public function SalvarUserHino(){

        $acesso_aprovado = $this->Crud_model->ValidarToken($this->uri->segment(5), 2);

        if ($acesso_aprovado) {

            $id_usuario = $acesso_aprovado;
            $id_hino = $this->uri->segment(4);

            $usuario = $this->Crud_model->Read('usuario', array('id_usuario' => $id_usuario));
            $hymn = $this->Crud_model->Read('hymn', array('id_hymn' => $id_hino));

            if ($usuario && $hymn) {

                $res = $this->Crud_model->Read('favoritos_usuario', array('id_hymn' => $id_hino, 'id_usuario' => $id_usuario));

                if (!$res) {
                    $this->Crud_model->Insert('favoritos_usuario', array('id_hymn' => $id_hino, 'id_usuario' => $id_usuario));

                } else {
                    $this->Crud_model->Delete('favoritos_usuario', array('id_hymn' => $id_hino, 'id_usuario' => $id_usuario));
                }

                echo json_encode(array('result' => "Sucesso"), JSON_UNESCAPED_UNICODE);;
                return;

            } else {
                $this->output->set_status_header('204');
                return;
            }

        }

        $this->output->set_status_header('401');

    }
}