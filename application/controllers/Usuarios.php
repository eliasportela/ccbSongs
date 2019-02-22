<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Usuarios extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function RegisterUser() {

        $chave = $this->uri->segment(3);
        $acesso_aprovado = $this->Crud_model->ValidarToken($chave, 1);

        if ($acesso_aprovado) {

            $data = $this->input->post();
            $json = "";

            $nome = isset($data['nome']) ? $data['nome'] : "";
            $email = isset($data['email']) ? $data['email'] : "";
            $senha = isset($data['senha']) ? $data['senha'] : "";


            $user = false;
            if ($email != "") {
                $user = $this->Crud_model->Read('usuario', array('email' => $email));
            }

            if (!$user) {

                $dataModel = array(
                    'nome' => $nome,
                    'email' => $email,
                    'senha' => $senha
                );

                $res = $this->User_model->Save($dataModel);

                if ($res) {
                    $json = json_encode($res, JSON_UNESCAPED_UNICODE);
                }

            } else {

                $this->output->set_status_header('401');
                $json = array('result' => 'Erro, o e-mail ja se encontra cadastrado.','chave' => null);
                $json = json_encode($json, JSON_UNESCAPED_UNICODE);
            }

            echo $json;
            return;

        }

        $this->output->set_status_header('401');

    }

    public function LoginUser() {

        $json = array();

        if ($this->input->get('chave') != null) {

            $chave = $this->input->get('chave');
            $acesso_aprovado = $this->Crud_model->ValidarToken($chave, 1);

            if ($acesso_aprovado) {
                $usuario = $this->Crud_model->Read('usuario', array('id_usuario' => $acesso_aprovado));

                $usuario = array(
                    'nome' => $usuario->nome,
                    'email' => $usuario->email
                );

                $json = array_merge($usuario, array('result' => 'success','chave' => $chave));

            } else {
                $json = array_merge([], array('result' => 'Sessão expirada! Faça o login novamente','chave' => null));
                $this->output->set_status_header('401');
            }

        } else {
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
                $this->output->set_status_header('401');
            }
        }

        echo json_encode($json, JSON_UNESCAPED_UNICODE);

    }

    public function UpdateUser() {

    }

    public function DeleteUser() {

    }

    public function UpdatePassword() {

    }
}