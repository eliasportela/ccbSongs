<template>
  <div>
    <c-header ref="header" voltar="/home"/>

    <div style="padding: 10px 0 140px">
      <div class="cell-row padding">
        <div class="cell left-align" style="width: 30%">
          <div class="card">
            <img src="../assets/img/fundo.jpg" class="image"/>
          </div>
        </div>
        <div class="cell cell-middle" style="width: 80%; padding-left: 12px">
          <span class="small"><b>{{cd.title}}</b></span><br>
          <span class="tiny">{{cd.singer}}</span><br>
        </div>
      </div>
      <div class="lista-hinos">
        <div class="cell-row" v-for="(h, index) in hinos" style="padding: 6px 0">
          <div class="cell left-align" :class="{'active':h===$refs.player.selecionado}" @click="$refs.player.selHino(h,index)" style="padding-left: 12px;width: 90%">
              {{h.title}} <br>
            <span class="tiny">{{cd.singer}}</span>
          </div>
          <div class="cell cell-middle center" style="width: 10%">
            <i class="fa fa-ellipsis-v"></i>
          </div>
        </div>
      </div>
    </div>
    <player ref="player" :token="token" :hinos="hinos"/>

  </div>
</template>

<script>
  import CHeader from '../components/Header'
  import Player from '../components/Player'

  export default {
    components: {CHeader,Player},
    name: "Home",
    data() {
      return {
        token: "",
        cd: [],
        hinos: [],
        selecionado: ""
      }
    },

    methods: {

      getHinos() {

        this.$http.get(base_url + 'cd/' + this.$route.params.id + '/' + this.token)
          .then(response => {
            this.cd = response.data;
            this.hinos = this.cd.hinos;

            if (this.$route.params.hymn !== undefined) {

              let indice = 0;
              let achou = false;
              this.hinos.forEach(res => {
                if (location.hash.includes(res.id_hymn)) {
                  this.$refs.player.selHino(res,indice);
                  achou = true;
                }
                indice++;
              });

              if (!achou) {
                this.$refs.player.selHino(this.hinos[0],0);
              }

            } else {
              this.$refs.player.selHino(this.hinos[0],0);
            }

          });
      },

    },

    mounted() {
      this.token = this.$refs.header.token;
      this.getHinos();
    }
  }
</script>

<style>
  .lista-hinos {
    border: 1px solid rgba(0,0,0,0.2);
    background-color: rgba(0,0,0,0.2);
    margin: 12px 12px 0;
    padding: 8px 0;
  }
  .active {
    color: #00cc7c;
    font-weight: bold;;
  }
  .active i {
    color: #00cc7c;
  }
</style>
