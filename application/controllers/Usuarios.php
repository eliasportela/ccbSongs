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
            $senha = isset($data['senha']) ? $data['senha'] : "";
            $picture = isset($data['picture']) ? $data['picture'] : "";
            $email = isset($data['email']) ? $data['email'] : "";
            $auth = isset($data['auth']) ? $data['auth'] : "";

            $user = false;
            if ($auth != "") {
                $user = $this->Crud_model->Read('usuario', array('auth' => $auth));
            }

            if (!$user) {

                $dataModel = array(
                    'nome' => $nome,
                    'email' => $email,
                    'senha' => $auth == "" ? $senha : null,
                    'auth' => $auth,
                    'picture' => $picture
                );

                $res = $this->User_model->Save($dataModel);

                if ($res) {
                    $json = json_encode($res, JSON_UNESCAPED_UNICODE);
                }

            } else {
                $token = $this->User_model->getToken($user->id_usuario);
                $json = array_merge(((array)$user), array('result' => 'success','chave' => $token));

            }

            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;

        }

        $this->output->set_status_header('401');

    }

    public function UpdateUser() {

    }

    public function DeleteUser() {

    }

    public function UpdatePassword() {

    }
}