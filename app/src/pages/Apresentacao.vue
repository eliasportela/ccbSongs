<template>
  <div>
    <c-header/>
    <div class="card-2 padding-16 center blue-grey" style="margin-top: 55px">
      <b>Bem Vindo ao CCB Songs Beta</b><br/>
      <b class="tiny">Algumas funcionalidades podem estar indisponíveis</b>
    </div>
    <div class="introducao card">
      <div class="text-introducao">
        <p>Escute mais de 20 mil hinos, compartilhe e salve seus favoritos.</p>
      </div>
    </div>
    <div class="cartao padding padding-32 center">
      <p><b>Para acessar crie sua conta. <br> É rápido, fácil e gratuito!</b></p>
      <div class="padding round facebook" @click="$router.push('/login')">
        Criar minha conta
      </div>
      <div class="tiny margin-top"><a href="#">Já sou cadastrado</a></div>
    </div>
    <div class="card-2 cartao-2 padding padding-32 center">
      <div class="text-introducao">
        <div class="cell-row padding">
          <div class="cell cell-middle" style="width: 33%">
            <i class="fa fa-youtube-play fa-2x"></i><br>
            <b class="small">YouTube</b>
          </div>
          <div class="cell cell-middle" style="width: 33%">
            <i class="fa fa-facebook-square fa-2x"></i><br/>
            <b class="small">Facebook</b>
          </div>
          <div class="cell cell-middle" style="width: 33%">
            <i class="fa fa-instagram fa-2x"></i><br/>
            <b class="small">Instagram</b>
          </div>
        </div>
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
    name: "Apresentacao",
    components: {CHeader},
    data() {
      return {
        categorias: []
      }
    },
    methods: {
    },
    mounted() {

    }
  }
</script>

<style scoped>
  .introducao {
    position: relative;
    background-image: url("../assets/img/2-min.png");
    background-repeat: no-repeat;
    background-size: 100% auto;
    text-align: center;
    font-weight: bold;
    height: 240px;
  }
  .text-introducao {
    position: absolute;
    left: 5%;
    bottom: 25px;
    width: 90%;
  }
  .facebook {
    border: 2px solid white;
    font-weight: bold;
    margin: 24px 0 12px;
  }
  .cartao {
    background-image: url("../assets/img/fundo.jpg");
    background-repeat: no-repeat;
    background-size: 100% 100%;
  }
  .cartao-2 {
    position: relative;
    background-image: url("../assets/img/4.jpg");
    background-repeat: no-repeat;
    background-size: 100% auto;
    height: 240px;
  }
  .cartao-3 {
    background-image: url("../assets/img/1.png");
    background-repeat: no-repeat;
    background-size: 100% auto;
    height: 240px;
  }
</style>
