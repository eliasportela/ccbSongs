<template>
  <div>
    <nav class="top cell-row card black" style="padding: 8px 20px 8px 0">
      <div class="cell cell-middle left-align" style="width: 50%">
        <button class="button" @click="$router.push(voltar)" v-show="voltar"><i class="fa fa-chevron-left"></i> <span style="padding-left: 4px">Home</span></button>
        <button class="button" @click="$router.push('/')" v-show="!voltar"><span style="padding-left: 4px; font-family: cursive">CCB Songs</span></button>
      </div>
      <div class="cell cell-middle right-align" style="width: 50%" @click="abrirMenu">
        <img class="image" src="../assets/icons/menu.svg" style="width: 20px; height: 20px"/>
      </div>
    </nav>
    <div class="modal" :class="{'show':menubar}" style="padding-top: 0">
      <div class="modal-content card-2 round black" style="margin: 0;height: 100%">
        <nav class="top cell-row card black" style="padding: 8px 2px 8px 20px">
          <div class="cell cell-middle" style="width: 50%">
            <span v-show="user !== ''">Meu Perfil</span>
            <span v-show="user === ''">Menu</span>
          </div>
          <div class="cell cell-middle right-align" style="width: 50%" @click="menubar = false">
            <button class="button"><i class="fa fa-times" style="font-size: 1.5em"></i></button>
          </div>
        </nav>
        <div class="container" style="padding-top: 70px">
          <div class="center padding" v-show="user !== ''">
            <div class="margin-bottom" style="padding: 0 32%">
              <div class="card-2 border">
                <img :src="user.picture" class="image"/>
              </div>
            </div>
            <div class="margin-bottom">{{user.nome}}</div>
          </div>
          <div class="cell-row card padding round margin-bottom" v-show="user !== ''" @click="irPagina('home')">
            <div class="cell">
              <span>Hinos</span><br>
              <span class="tiny">Acesse todos os hinos</span>
            </div>
            <div class="cell cell-middle left-align" style="width: 5%">
              <i class="fa fa-chevron-right"></i>
            </div>
          </div>
          <div class="cell-row card padding round margin-bottom" v-show="user !== ''">
            <div class="cell">
              <span>Meus Hinos</span><br>
              <span class="tiny">Acesse os Hinos favoritos</span>
            </div>
            <div class="cell cell-middle left-align" style="width: 5%">
              <i class="fa fa-chevron-right"></i>
            </div>
          </div>
          <!--<div class="cell-row card padding round margin-bottom">-->
            <!--<div class="cell">-->
              <!--<span>Doar</span><br>-->
              <!--<span class="tiny">Gostou do CCB Songs? Saiba como ajudar</span>-->
            <!--</div>-->
            <!--<div class="cell cell-middle left-align" style="width: 5%">-->
              <!--<i class="fa fa-chevron-right"></i>-->
            <!--</div>-->
          <!--</div>-->
          <div class="cell-row card padding padding-16 round margin-bottom" @click="irPagina('/login')" v-show="user === ''">
            <div class="cell">
              <span>Logar</span><br/>
              <span class="tiny">Faça o login para acessar todos os recursos</span>
            </div>
            <div class="cell cell-middle left-align" style="width: 5%">
              <i class="fa fa-sign-in"></i>
            </div>
          </div>
          <div class="cell-row card padding padding-16 round margin-bottom" @click="irPagina('/login')" v-show="user !== ''">
            <div class="cell">
              <span>Sair</span>
            </div>
            <div class="cell cell-middle left-align" style="width: 5%">
              <i class="fa fa-sign-out"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="center tiny bottom" style="margin: 15px 0">
        <div style="margin-bottom: 4px">2019 - CCB Songs - Todos os direitos reservados</div>
        <a href="#" style="padding-right: 15px">Termos de Uso</a> <a href="#">Privacidade</a>
      </div>
    </div>
    <div style="margin-top: 55px">
    </div>
  </div>
</template>

<script>
  export default {
    name: "CHeader",
    props: {
      voltar: false
    },
    data() {
      return {
        token: "",
        menubar: false,
        user : ""
      }
    },
    methods: {
      irPagina(page){
        this.menubar = false;
        this.$router.push(page)
      },

      buscarUserLogado() {
        if (sessionStorage.getItem('usuario')) {
          this.user = JSON.parse(sessionStorage.getItem('usuario'));
          this.token = this.user.chave;
        }
      },

      abrirMenu() {
        this.buscarUserLogado();
        this.menubar = true;
      },
    },
    created() {
      this.buscarUserLogado();

      if (this.user === "" && !(this.$route.path === "/" || this.$route.path === "/login")) {
        this.$router.push("/");
      }

    }

  }
</script>

<style scoped>
  .voltar {
    padding: 0 5px;
  }
</style>
