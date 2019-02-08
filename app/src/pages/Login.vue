<template>
  <div>
    <c-header/>
    <div class="container">
      <div class="margin-top">
        <div class="center">
          <h4><b>Bem vindo ao CCB Songs</b></h4>
          <p class="center small">Ja é cadastrado ?</p>
          <button class="button white block round">Faça seu login</button>
          <p class="center">Ou</p>
          <p class="small">Cadastre-se para acessar o CCB Songs. É gratís ;)</p>
        </div>
        <form @submit.prevent="register">
          <div class="margin-bottom">
            <label>Nome</label>
            <input type="text" v-model="dados.nome" class="input nome" placeholder="Informe seu nome" required/>
          </div>
          <div class="margin-bottom">
            <label>E-mail</label>
            <input type="email" v-model="dados.email" class="input email" placeholder="Informe seu e-mail" required/>
          </div>
          <div class="margin-bottom">
            <label>Senha</label>
            <input type="password" v-model="dados.senha" class="input" placeholder="Informe uma senha" minlength="6" required/>
          </div>
          <div class="margin-bottom">
            <label>Repita sua senha</label>
            <input type="password" v-model="senhaRepetida" class="input" placeholder="Repita a senha" minlength="6" required/>
          </div>
          <button class="button white block round">Cadastrar</button>
        </form>
      </div>
    </div>
    <div class="card-2 padding padding-32 center">
      <div class="center tiny">
        <div style="margin-bottom: 12px"><b>2019 - CCB Songs - Todos os direitos reservados</b></div>
        <a href="#" style="padding-right: 15px"><b>Termos de Uso</b></a> <a href="#"><b>Privacidade</b></a>
      </div>
    </div>
  </div>
</template>

<script>
  import CHeader from '../components/Header'

  export default {
    name: "Login",
    components: {CHeader},
    data() {
      return {
        senhaRepetida: "",
        dados: {
          nome: "",
          email: "",
          senha: "",
        }
      }
    },

    methods: {
      register() {
        if (this.dados.email !== "" && this.dados.nome.length > 5 && this.dados.senha.length > 6) {

          console.log("teste");
          if (this.dados.senha !== this.senhaRepetida) {
            alert("Senha devem ser iguais");
            return;
          }

          this.$http.post(base_url + 'register/' + token, this.dados, {emulateJSON: true})
            .then(res => {
              sessionStorage.setItem('usuario', JSON.stringify(res.data));
              this.$router.push("/home");
            });
        }
      },

      getUserData() {
        this.$http.post(base_url + 'register/' + token, this.dados, {emulateJSON: true})
          .then(res => {
            this.dados.email = res.email;
            this.dados.nome = res.name;
            this.dados.auth = res.id;
            this.register();
          }
        )
      },

    }
  }
</script>

<style scoped>
  .input {
    background-color: #4f5155f5!important;
    color: white;
  }
  .input.nome {
    text-transform: capitalize;
  }
  .input.email {
    text-transform: lowercase;
  }
</style>
