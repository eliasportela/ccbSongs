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
            <hr>
            <div class="margin-bottom cell-row padding blue round" @click="logarFacebook">
              <div class="cell cell-middle">
                <i class="fa fa-facebook"></i>
              </div>
              <div class="cell cell-middle">
                Logar com o Facebook
              </div>
            </div>
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
          email: "",
          senha: "",
          picture: "",
          nome: "",
          auth: ""
        },

        modalInfo: false
      }
    },
    methods: {
      showModal() {
        this.modalInfo = true;
      },

      fecharModais() {
        this.modalInfo = false;
      },

      register() {
        console.log(this.dados);
        this.$http.post(base_url + 'register/' + token, this.dados, {emulateJSON: true})
          .then(res => {
            this.logar(res);
            this.$emit('salvarHino');
            this.fecharModais();
          });
      },

      logar(res) {
        sessionStorage.setItem('usuario', JSON.stringify(res.data));
      },

      logarFacebook() {

        FB.getLoginStatus(response => {
          if (response.status === 'connected') {

            let userId = response.authResponse.userID;

            FB.api(userId, { fields: ['email','name','picture.width(720)']}, user => {
              if (user && !user.error) {
                this.dados.email = user.email;
                this.dados.nome = user.name;
                this.dados.senha = "";
                this.dados.picture = user.picture.data.url;
                this.dados.auth = userId;
                this.register();
              }
            });

          }

        }, true);

        FB.Event.subscribe('auth.statusChange', response => {
          if (response === 'connected') {
            FB.login(res => {
              if (response.authResponse) {
                let userId = response.authResponse.userID;

                FB.api(userId, { fields: ['email','name','picture.width(720)']}, user => {
                  if (user && !user.error) {
                    this.dados.email = user.email;
                    this.dados.nome = user.name;
                    this.dados.senha = "";
                    this.dados.picture = user.picture.data.url;
                    this.dados.auth = userId;
                    this.register();
                  }
                });

              }
            });
          }
        });
      }

    },

    mounted(){
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
