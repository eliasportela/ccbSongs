<template>
  <div>
    <c-header/>
    <div class="container">
      <div class="center">
        <h4><b>Bem vindo ao CCB Songs</b></h4>
        <p class="margin-bottom">
          <span>Para acessar entre com sua conta do facebook</span>
        </p>
        <hr>
      </div>
      <facebook-login class="button"
                      appId="255504468465604"
                      loginLabel="Entrar com o Facebook"
                      @login="onLogin"
                      @logout="onLogout"
                      @sdk-loaded="sdkLoaded">
      </facebook-login>
    </div>
    <div class="bottom card-2 padding padding-32 center">
      <div class="center tiny">
        <div style="margin-bottom: 12px"><b>2019 - CCB Songs - Todos os direitos reservados</b></div>
        <a href="#" style="padding-right: 15px"><b>Termos de Uso</b></a> <a href="#"><b>Privacidade</b></a>
      </div>
    </div>

  </div>
</template>

<script>
  import CHeader from '../components/Header'
  import facebookLogin from 'facebook-login-vuejs';

  export default {
    name: "Login",
    components: {CHeader, facebookLogin},
    data() {
      return {
        dados: {
          email: "",
          senha: "",
          picture: "",
          nome: "",
          auth: ""
        },

        isConnected: false,
        FB: undefined,
      }
    },

    methods: {
      register() {
        this.$http.post(base_url + 'register/' + token, this.dados, {emulateJSON: true})
          .then(res => {
            sessionStorage.setItem('usuario', JSON.stringify(res.data));
            this.$router.push("/home");
          });
      },

      getUserData() {
        this.FB.api('/me', 'GET', { fields: 'id,email,name,picture.width(720)' },
          user => {
            this.dados.email = user.email;
            this.dados.nome = user.name;
            this.dados.senha = "";
            this.dados.picture = user.picture.data.url;
            this.dados.auth = user.id;
            this.register();
          }
        )
      },

      sdkLoaded(payload) {
        this.FB = payload.FB;
        if(payload.isConnected) this.getUserData();
      },

      onLogin() {
        this.getUserData()
      },

      onLogout() {
        sessionStorage.clear();
        this.FB.logout();
      }

    }
  }
</script>

<style scoped>
</style>
