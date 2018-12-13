<template>
  <div>
    <c-header/>
    <div class="introducao card">
      <div class="text-introducao">
        <p>Escute mais de 20 mil hinos, compartilhe e salve seus favoritos com o CCB Songs</p>
        <div class="cell-row padding round facebook" @click="logarFacebook">
          <div class="cell cell-middle">
            <i class="fa fa-facebook"></i>
          </div>
          <div class="cell cell-middle">
            Acessar com o Facebook
          </div>
        </div>
      </div>
    </div>
    <div class="card-2 cartao padding padding-32 center">
      <div class="cell-row padding" @click="logarFacebook">
        <div class="cell cell-middle">
            <i class="fa fa-youtube-square fa-2x"></i>
        </div>
        <div class="cell cell-middle">
            <i class="fa fa-facebook-square fa-2x"></i>
        </div>
        <div class="cell cell-middle">
          <i class="fa fa-instagram fa-2x"></i>
        </div>
      </div>
    </div>
    <div class="card-2 cartao-2 padding padding-32 center">
    </div>
  </div>
</template>

<script>
  import CHeader from '../components/Header'

  export default {
    name: "Apresentacao",
    components: {CHeader},
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
  .introducao {
    position: relative;
    margin-top: 55px;
    background-image: url("../assets/img/2.png");
    background-repeat: no-repeat;
    background-size: 100% auto;
    text-align: center;
    font-weight: bold;
    height: 240px;
  }
  .text-introducao {
    position: absolute;
    left: 5%;
    bottom: 20px;
    width: 90%;
  }
  .facebook {
    border: 2px solid white;
    font-weight: bold;
  }
  .cartao {
    background-image: url("../assets/img/fundo.jpg");
    background-repeat: no-repeat;
    background-size: 100%;
  }
  .cartao-2 {
    background-image: url("../assets/img/4.png");
    background-repeat: no-repeat;
    background-size: 100% auto;
    height: 240px;
  }
  .cartao-2 {
    background-image: url("../assets/img/4.png");
    background-repeat: no-repeat;
    background-size: 100% auto;
    height: 240px;
  }
</style>
