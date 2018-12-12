<template>
  <div>
    <div>
      <div class="introducao">
        <img class="logo" src="../assets/img/logo.png"/>
        <p>Acesse mais de 20 mil hinos, crie sua lista de favoritos, compartilhe e muito mais.</p>
      </div>
      <div class="container">
        <div style="margin: 0 10%">
          <div class="cell-row padding round facebook" @click="logarFacebook">
            <div class="cell cell-middle">
              <i class="fa fa-facebook"></i>
            </div>
            <div class="cell cell-middle">
              Entrar com o Facebook
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "Apresentacao",
    data() {
      return {
        categorias: []
      }
    },
    methods: {
      logarFacebook() {

      }
    },
    mounted() {

    }
  }
</script>

<style scoped>
  .container {
    position: absolute;
    top: 50px;
    width: 100%;
    text-align: center;
  }
  .logo {
    width: 100%;
    height: 200px;
    object-fit: cover;
  }
  .introducao {
    background-image: url("../assets/img/fundo.jpg");
    margin: 20px 0 30px;
  }
  .facebook {
    /*border: 2px solid #3b5998;*/
    border: 2px solid white;
    font-weight: bold;
  }
</style>
