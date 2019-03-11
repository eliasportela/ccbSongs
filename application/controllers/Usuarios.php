<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Usuarios extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function RegisterUser() {

        $data = $this->input->post();
        $nome = isset($data['nome']) ? $data['nome'] : false;
        $email = isset($data['email']) ? $data['email'] : false;
        $senha = isset($data['senha']) ? $data['senha'] : false;
        $json = array('result' => 'Erro nos parâmetros passados!');

        if ($nome && $email && $senha) {

            if (!$this->Crud_model->Read('usuario', array('email' => $email))) {

                $dataModel = array(
                    'nome' => $nome,
                    'email' => $email,
                    'senha' => $senha
                );

                $res = $this->User_model->Save($dataModel);

                if ($res) {
                    $json = $res;
                }

            } else {

                $this->output->set_status_header('401');
                $json = array('result' => 'Erro, o e-mail ja se encontra cadastrado.', 'chave' => null);
            }
        }

        $json = json_encode($json, JSON_UNESCAPED_UNICODE);
        echo $json;

    }

    public function LoginUser() {

        $json = array('result' => 'Erro nos parâmetros passados!');
        $data = $this->input->post();
        $email = isset($data['email']) ? $data['email'] : "";
        $senha = isset($data['senha']) ? $data['senha'] : "";

        if ($email != "" && $senha != "") {
            $dataModel = array(
                'email' => $email,
                'senha' => $senha
            );

            $json = $this->User_model->Login($dataModel);

        } else {
            $this->output->set_status_header('400');
        }

        echo json_encode($json, JSON_UNESCAPED_UNICODE);

    }

    public function UpdateUser() {

    }

    public function DeleteUser() {

    }

    public function UpdatePassword() {

    }

    public function GetUser() {

        $chave = $this->uri->segment(3);
        $acesso_aprovado = $this->Crud_model->ValidarToken($chave, 1);

        $json = array('result' => 'Sessão expirada. Faça o login novamente!');
        $json = json_encode($json, JSON_UNESCAPED_UNICODE);

        if ($acesso_aprovado) {

            $user = $this->Crud_model->Query("SELECT id_usuario, nome, email, picture, administrativo FROM usuario WHERE id_usuario = $acesso_aprovado");

            if ($user) {
                $json = json_encode($user[0], JSON_UNESCAPED_UNICODE);
            }

        } else {
            $this->output->set_status_header('401');
        }

        echo $json;
    }
}