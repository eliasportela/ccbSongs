<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class User_model extends CI_Model
{
	function __construct() {
		parent::__construct();
	}

	function Save($data) {

	    if ($data['senha'] != null) {
            $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }

        $this->db->insert('usuario',$data);
        $res = $id = $this->db->insert_id();

        if ($res) {

            $token = $this->getToken($res);

            if ($token !== FALSE) {
                return array('result' => 'success','chave' => $token);
            }

		}

		return false;

	}

    function UpdateSenha($data,$p) {
		$data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
		$this->db->where($p);
		$this->db->update('usuario',$data);
	}

	function Login($data) {
		$par = array('email' => $data['email'], 'fg_ativo' => 1);
		$this->db->select('*')->from('usuario')->where($par);
		$res = $this->db->get()->result();

		if ($res) {

            foreach($res as $result) {

                if (password_verify($data['senha'], $result->senha)) {
                    $token = $this->getToken($result->id_usuario);
                    return array('result' => 'success','chave' => $token);

                } else {
                    $this->output->set_status_header('401');
                    return array('result' => 'Senha incorreta','chave' => null);
                }
            }

        }

        $this->output->set_status_header('401');
        return array('result' => 'E-mail não cadastrado','chave' => null);

	}

    function getToken($id_usuario) {

        $datetime = new DateTime();
        $data_agora = $datetime->format('Y-m-d H:i:s');

        $res = $this->Crud_model->Query("SELECT chave FROM token WHERE id_usuario = $id_usuario AND DATE(data_expiracao) > '".$data_agora."'");

        if ($res) {
            $chave = $res[0]->chave;

        } else {

            $chave = md5(uniqid(rand(), true));
            $datetime->modify('+1 day');
            $data_expiracao = $datetime->format('Y-m-d H:i:s');

            $data_model = array(
                'data_acesso' => $data_agora,
                'data_expiracao' => $data_expiracao,
                'chave' => $chave,
                'id_usuario' => $id_usuario
            );

            $this->Crud_model->Insert('token', $data_model);
        }

        return $chave;

    }

}