<template>
  <div>
    <nav class="top cell-row card black" style="padding: 8px 20px 8px 0">
      <div class="cell cell-middle left-align" style="width: 50%">
        <button class="button" @click="$router.push('/')"><i class="fa fa-chevron-left"></i> <span style="padding-left: 4px">Home</span></button>
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
          </div>
          <div class="cell cell-middle right-align" style="width: 50%" @click="menubar = false">
            <button class="button"><span style="padding-right: 4px">Fechar</span><i class="fa fa-times"></i></button>
          </div>
        </nav>
        <div class="container" style="padding-top: 70px">
          <div class="padding" v-show="user !== ''">
            <div class="center margin-bottom " style="padding: 0 32%">
              <div class="card-2 border">
                <img :src="user.picture" class="image"/>
              </div>
            </div>
            <div class="cell-row margin-bottom">
              <div class="cell" style="width: 10%">
                <i class="fa fa-user"></i>
              </div>
              <div class="cell">
                {{user.nome}}
              </div>
            </div>
            <div class="cell-row">
              <div class="cell" style="width: 10%">
                <i class="fa fa-envelope"></i>
              </div>
              <div class="cell">
                {{user.email}}
              </div>
            </div>
          </div>
          <div class="cell-row card padding round margin-bottom">
            <div class="cell">
              <span>Meus Hinos</span><br>
              <span class="tiny">Acesse todos os Hinos marcados como "Amei"</span>
            </div>
            <div class="cell cell-middle left-align" style="width: 5%">
              <i class="fa fa-chevron-right"></i>
            </div>
          </div>
          <div class="cell-row card padding round margin-bottom">
            <div class="cell">
              <span>Doar</span><br>
              <span class="tiny">Gostou do CCB Songs? Saiba como ajudar</span>
            </div>
            <div class="cell cell-middle left-align" style="width: 5%">
              <i class="fa fa-chevron-right"></i>
            </div>
          </div>
          <div class="cell-row card padding padding-16 round margin-bottom" @click="sair" v-show="user === ''">
            <div class="cell">
              <span>Logar</span><br/>
              <span class="tiny">Faça o login para acessar todos os recursos</span>
            </div>
            <div class="cell cell-middle left-align" style="width: 5%">
              <i class="fa fa-sign-in"></i>
            </div>
          </div>
          <div class="cell-row card padding padding-16 round margin-bottom" @click="sair" v-show="user !== ''">
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
  </div>
</template>

<script>
  export default {
    name: "CHeader",
    data() {
      return {
        menubar: false,
        user : ""
      }
    },
    methods: {
      buscarUserLogado() {
        if (sessionStorage.getItem('usuario')) {
          this.user = JSON.parse(sessionStorage.getItem('usuario'));
        }
      },
      abrirMenu() {
        this.buscarUserLogado();
        this.menubar = true;
      },
      sair() {
        sessionStorage.clear();
        this.menubar = false;
        this.user = "";
      }
    },
    mounted() {
      this.buscarUserLogado();
    }

  }
</script>

<style scoped>
  .voltar {
    padding: 0 5px;
  }
</style>
