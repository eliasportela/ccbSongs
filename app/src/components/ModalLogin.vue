<template>
  <div>

    <div class="modal" :class="{'show':modalInfo}">
      <div class="modal-content card-2 round black padding padding-32">
        <div class="container">
          <div class="fechar" @click="fecharModais">
            <i class="fa fa-times text-white"></i>
          </div>
          <div class="center" style="margin-top: 40px">
            <h4><b>Bem vindo ao CCB Songs</b></h4>
            <p class="margin-bottom">
              Salve os seus hinos favoritos e depois escute-os na área de <b>Hinos Salvos</b>
            </p>
            <br>
            <a href="javascript:" class="button block border border-white round margin-bottom" @click="modalCadastro = true">Quero me cadastrar</a>
            <a href="javascript:" class="button block border border-white round margin-bottom" @click="modalLogin = true">Fazer Login</a>
            <hr>
            <div class="margin-bottom">
              <div class="fb-login-button" data-max-rows="1" data-size="large" data-button-type="login_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false" data-onlogin=""></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" :class="{'show':modalCadastro}">
      <div class="modal-content card-2 round black padding">
        <div class="container" style="padding-bottom: 16px">
          <div class="fechar" @click="fecharModais">
            <i class="fa fa-times text-white"></i>
          </div>
          <div class="margin-bottom">
            <div style="margin-bottom: 24px">
              <h4><b>Cadastro</b></h4>
            </div>
            <form class="margin-top">
              <div class="input-form">
                <input type="text" class="input border black" v-model="dados.usuario" placeholder="Informe seu e-mail"/>
                <i class="fa fa-envelope text-white"></i>
              </div>
              <div class="input-form">
                <input type="password" class="input border black" v-model="dados.senha" placeholder="Informe sua senha"/>
                <i class="fa fa-lock text-white"></i>
              </div>
              <div class="input-form">
                <input type="password" class="input border black" v-model="dados.senha" placeholder="Repita sua senha"/>
                <i class="fa fa-lock text-white"></i>
              </div>
            </form>
            <hr>
            <a href="#" class="button block border border-white round margin-bottom">Cadastrar</a>
          </div>
        </div>
      </div>
    </div>


    <div class="modal" :class="{'show':modalLogin}">
      <div class="modal-content card-2 round black padding">
        <div class="container" style="padding-bottom: 16px">
          <div class="fechar" @click="fecharModais">
            <i class="fa fa-times text-white"></i>
          </div>
          <div class="margin-bottom">
            <div style="margin-bottom: 24px">
              <h4><b>Login</b></h4>
            </div>
            <form class="margin-top">
              <div class="input-form">
                <input type="text" class="input border black" v-model="dados.usuario" placeholder="Informe seu e-mail"/>
                <i class="fa fa-envelope text-white"></i>
              </div>
              <div class="input-form">
                <input type="password" class="input border black" v-model="dados.senha" placeholder="Informe sua senha"/>
                <i class="fa fa-lock text-white"></i>
              </div>
            </form>
            <hr>
            <a href="#" class="button block border border-white round">Login</a>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
  export default {
    name: "modal-login",
    data() {
      return {
        dados: {
          usuario: "",
          senha: ""
        },
        modalLogin: false,
        modalCadastro: false,
        modalEsqueceuSenha: false,
        modalInfo: false
      }
    },
    methods: {
      showModal(modal) {
        this.modalInfo = false;
        this.modalCadastro = false;
        this.modalEsqueceuSenha = false;
        this.modalLogin = false;

        switch (modal) {
          case 1:
            this.modalInfo = true;
            FB.getLoginStatus();
            break;
          case 2:
            this.modalCadastro = true;
            this.logarFacebook();
            break;
          case 3:
            this.modalLogin = true;
            break;
          case 4:
            this.modalEsqueceuSenha = true;
            break;
        }

      },

      fecharModais() {
        this.modalInfo = false;
        this.modalCadastro = false;
        this.modalEsqueceuSenha = false;
        this.modalLogin = false;
      },

      logar() {

      },

      logarFacebook() {
        FB.getLoginStatus(function (response) {
          console.log(response);
        });
      }

    },

    mounted(){
      window.checkLoginState = this.logarFacebook()
    },
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
