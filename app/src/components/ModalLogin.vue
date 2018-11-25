<template>
  <div class="modal" :class="{'show':showModal}">
    <div class="modal-content card-2 black padding padding-32">
      <div class="container">
        <div class="fechar" @click="fecharModais">
          <i class="fa fa-times text-white"></i>
        </div>
        <div class="margin-bottom">
          <img src="../assets/img/logo.png" class="image"/>
        </div>
        <form>
          <div class="input-form">
            <input type="text" class="light-gray round" v-model="dados.usuario" placeholder="Informe seu usuário"/>
            <i class="fa fa-user text-black"></i>
          </div>
          <div class="input-form">
            <input type="password" class="light-gray round" v-model="dados.senha" placeholder="Informe sua senha"/>
            <i class="fa fa-lock text-black"></i>
          </div>
          <button type="button" class="button white block round login-btn" :disabled="(dados.usuario === '') || (dados.senha === '')" @click="logar(true)">
            Login
          </button>
        </form>
        <div class="margin-top center">
          <hr>
          <a href="#" class="button block border border-white round margin-bottom">Quero me cadastrar</a>
          <a href="#">Esqueceu sua senha?</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "modal-login",
    props: {
      showModal: Boolean
    },
    data() {
      return {
        dados: {
          usuario: "",
          senha: ""
        }
      }
    },
    methods: {
      logar() {

      },
      fecharModais() {
        this.showModal = false;
      }
    }
  }
</script>

<style scoped>
  .fechar {
    position: absolute;
    right: 24px;
    top: 16px;
    font-size: 1.5em;
  }
  .image {
    width: 600px;
    height: 120px;
    object-fit: cover;
    object-position: center;
  }
  .input-form {
    margin: 12px 0 16px;
    border-bottom: 1px solid #f44336;
    position: relative;
  }
  .input-form input{
    border: none;
    width: 100%;
    padding: 12px;
    font-size: 1.1em;
  }
  .input-form i {
    position: absolute;
    right: 16px;
    top: 12px;
    font-size: 20px;
  }
  .login-btn {
    margin-top: 32px;
    font-size: 1.1em;
  }
</style>
