<template>
  <div>
    <nav class="top cell-row card black" style="padding: 16px 20px">
      <div class="cell" style="width: 50%" @click="menubar = true">
        <img class="image" src="../assets/icons/menu.svg" style="width: 20px; height: 20px"/>
      </div>
      <div class="cell right-align" style="width: 50%">
        <img class="image" src="../assets/icons/more.svg" style="width: 20px; height: 20px"/>
      </div>
    </nav>
    <div class="modal" :class="{'show':menubar}" style="padding-top: 0">
      <div class="modal-content card-2 round black" style="margin: 0;height: 100%">
        <nav class="top cell-row card black" style="padding: 16px 20px">
          <div class="cell cell-middle" @click="menubar = false">
            <i class="fa fa-chevron-left voltar"></i> <span class="voltar">VOLTAR</span>
          </div>
        </nav>
        <div class="container" style="padding-top: 70px">
          <div class="padding padding-16">
            <div class="margin-bottom center small"><b>Meu perfil</b></div>
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
          <div class="cell-row card padding padding-16 round margin-bottom">
            <div class="cell">
              <span>Sair</span>
            </div>
            <div class="cell cell-middle left-align" style="width: 5%" @click="sessionStorage.clear()">
              <i class="fa fa-sign-out"></i>
            </div>
          </div>
          <div class="center tiny" style="margin-top: 30px">
            <div class="margin-bottom">2019 - CCB Songs - Todos os direitos reservados</div>
            <a href="#" style="padding-right: 15px">Termos de Uso</a> <a href="#">Privacidade</a>
          </div>
        </div>

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
        user : []
      }
    },
    methods: {
      buscarUserLogado() {
        if (sessionStorage.getItem('usuario')) {
          this.user = JSON.parse(sessionStorage.getItem('usuario'));
        }
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
