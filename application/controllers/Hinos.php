<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Hinos extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_content_type('application/json');
    }

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

    public function GetFavoritos(){

        $acesso_aprovado = $this->Crud_model->ValidarToken($this->uri->segment(3), 1);

        if ($acesso_aprovado) {

            $sql = "select h.id_hymn, c.id_cd, c.title as cd, h.title, h.url from favoritos_usuario f
              INNER JOIN hymn h ON (h.id_hymn = f.id_hymn)
              INNER JOIN cd c ON (c.id_cd = h.id_cd) LIMIT 50";
            $hinos = $this->Crud_model->Query($sql);

            $json = array('hinos' => $hinos);

            echo json_encode($json, JSON_UNESCAPED_UNICODE);;
            return;
        }

        $this->output->set_status_header('401');

    }

    public function GetCategoriasHinos(){

        $chave = $this->uri->segment(3);
        $acesso_aprovado = $this->Crud_model->ValidarToken($chave, 1);

        if ($acesso_aprovado) {

            $sql = "SELECT id_category, name as category FROM category WHERE fg_ativo = 1";
            $categorias = $this->Crud_model->Query($sql);

            if ($categorias) {

                $json = [];
                foreach ($categorias as $c) {

                    $sql = "SELECT id_cd, title FROM cd WHERE id_category = '$c->id_category' ORDER BY qtd_canticos DESC LIMIT 20";
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

    public function GetCategoria(){

        $chave = $this->uri->segment(3);
        $acesso_aprovado = $this->Crud_model->ValidarToken($chave, 1);
        $json = array('result' => 'Sessão expirada! Faça o login novamente');

        if ($acesso_aprovado) {

            $data = $this->input->get();

            $id_categoria = isset($data['id_category']) ? $data['id_category'] : null;
            $page = isset($data['page']) ? $data['page'] : 1;

            $json = array('result' => 'Erro nos parâmetros passados!');
            if ($id_categoria != null) {

                $sql = $this->Crud_model->Query("SELECT count(*) as qtd FROM cd WHERE id_category = $id_categoria and fg_ativo = 1");
                $pages = round($sql[0]->qtd / 20);

                $sql = $this->Crud_model->Query("SELECT id_category, name as category FROM category WHERE id_category = $id_categoria and fg_ativo = 1");

                if ($sql) {
                    $categorias = (array)$sql[0];

                    $page = $page > 0 ? $page - 1 : 0;

                    $sql = "SELECT c.id_cd, c.title, s.name as singer
                            FROM cd c
                            INNER JOIN singer s ON (c.id_singer = s.id_singer)
                            WHERE c.id_category = $id_categoria ORDER BY c.qtd_canticos DESC LIMIT 20 offset ". ($page * 20);

                    $cds = $this->Crud_model->Query($sql);

                    $categorias = array_merge($categorias,array('page' => $page + 1,'total_pages' => $pages > 0 ? $pages : 1));

                    if (!$cds){
                        $cds = [];
                    }

                    $json = array_merge($categorias, array('cds' => $cds));

                } else {
                    $this->output->set_status_header('204');
                }
            }

        } else {
            $this->output->set_status_header('401');
        }

        echo json_encode($json, JSON_UNESCAPED_UNICODE);

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