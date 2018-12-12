<template>
  <div>
    <c-header/>
    <div class="center" style="padding-top: 70px">
      <div style="padding-bottom: 200px">
        <div class="cell-row padding" v-for="(h, index) in hinos">
          <div class="cell left-align" :class="{'active':h===selecionado}" @click="selHino(h,index)" style="padding-left: 12px;width: 90%">
              {{h.title}} <br>
            <span class="tiny">{{cd.singer}}</span>
          </div>
          <div class="cell cell-middle" style="width: 10%">
            <i class="fa fa-spinner fa-pulse" v-show="load && h===selecionado"></i>
          </div>
        </div>
      </div>
      <div class="bottom card black" style="padding-top: 15px">
        <div class="margin-bottom">
          <span class="small">{{selecionado.title}}</span>
        </div>
        <div class="cell-row controlers">
          <div class="cell cell-middle" style="width: 33%" @click="backHino">
            <i class="fa fa-step-backward" style="font-size: 1.5em"></i>
          </div>
          <div class="cell cell-middle" style="width: 33%" @click="tooglePlay">
            <i class="fa" :class="paused ? 'fa-play' : 'fa-pause'" style="font-size: 3em"></i>
          </div>
          <div class="cell cell-middle" style="width: 33%" @click="nextHino">
            <i class="fa fa-step-forward" style="font-size: 1.5em"></i>
          </div>
        </div>
        <div class="margin">
          <div id="timeline">
            <span id="current-time">{{currentTime}}</span>
            <span id="total-time">{{totalTime}}</span>
            <div id="slider" class="slider" data-direction="horizontal" @click="rewind">
              <div class="progress" :style="{'width':progress}">
                <div class="pin" id="progress-pin"></div>
              </div>
            </div>
          </div>
          <audio crossorigin id="audioplayer">
            <source src="" type="audio/mpeg">
          </audio>
        </div>

        <div class="cell-row cell-middle subcontroller">
          <div class="cell" style="width: 20%" :class="aleatorio ? 'active' : ''" @click="aleatorio = !aleatorio">
            <i class="fa fa-random"></i>
            <span class="tiny sublegenda">Aleatório</span>
          </div>
          <div class="cell" style="width: 20%" :class="automatico ? 'active' : ''" @click="automatico = !automatico">
            <i class="fa fa-refresh"></i>
            <span class="tiny sublegenda">Automático</span>
          </div>
          <div class="cell" style="width: 20%">
            <i class="fa fa-cloud-download"></i>
            <span class="tiny sublegenda">Download</span>
          </div>
          <div class="cell" style="width: 20%">
            <i class="fa fa-share"></i>
            <span class="tiny sublegenda">Compartilhar</span>
          </div>
          <div class="cell" style="width: 20%" @click="ameiHino">
            <i class="fa" :class="amei ? 'fa-heart active' : 'fa-heart-o'"></i>
            <span class="tiny sublegenda">Amei</span>
          </div>
        </div>
      </div>
    </div>

    <login ref="login" @salvarHino="ameiHino"/>

  </div>
</template>

<script>
  import CHeader from '../components/Header'
  import Login from '../components/ModalLogin'

  export default {
    components: {CHeader,Login},
    name: "Home",
    data() {
      return {
        token: "",
        logado: false,
        user: [],
        modalLogin: false,
        cd: [],
        hinos: [],
        selecionado: "",
        player: '',
        load: true,
        paused: true,
        progress: '0',
        currentTime: "0:00",
        totalTime: "0:00",
        aleatorio: false,
        automatico: true,
        amei: false,
        indice: 0
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
                  this.selHino(res,indice);
                  achou = true;
                }
                indice++;
              });

              if (!achou) {
                //this.$router.push("/");
              }

            } else {
              this.selHino(this.hinos[0],0);
            }

          });
      },

      selHino(h, indice) {
        this.load = true;
        this.$http.get(base_url + 'hino/' + h.id_hymn + '/' + this.token)
          .then(response => {
            this.selecionado = h;
            this.amei = response.data.favorito;
            this.player.setAttribute("src", h.url);
            this.indice = indice;

            this.$router.push("/reproducao/" + this.$route.params.id + "/" + h.id_hymn);

            this.tooglePlay();
          });
      },

      tooglePlay() {
        if (this.player.paused) {

          if (this.selecionado === "") {
            this.selHino(this.hinos[0], 0);
            return;
          }

          this.player.addEventListener('loadedmetadata', () => {
            this.totalTime = this.formatTime(this.player.duration);
            this.paused = false;
            this.load = false;

          });

          this.player.addEventListener('timeupdate', this.updateProgress);

          this.player.addEventListener('ended', () => {
            if (this.automatico) {
              this.nextHino();
            } else {
              this.paused = true;
            }
          });

          this.player.addEventListener('onplaying', () => {
            console.log("Play")
          });

          this.player.play();

        } else {
          this.player.pause();
          this.paused = true;
        }
      },

      updateProgress() {
        let current = this.player.currentTime;
        let percent = (current / this.player.duration) * 100;
        this.progress = percent + "%";

        this.currentTime = this.formatTime(current);

      },

      getCoeficiente(position) {
        let width = document.getElementById("slider").offsetWidth;
        let padding = (window.innerWidth - width) / 2;

        return (((position - padding)/ width) * 100);
      },

      rewind(event) {
        let coeficiente = this.getCoeficiente(event.clientX);
        let position = (coeficiente / 100) * this.player.duration;

        this.player.currentTime = position;

      },

      nextHino() {
        if (!this.aleatorio) {
          if ((this.indice + 1) >= this.hinos.length) {
            this.selHino(this.hinos[0], 0);

          } else {
            this.indice++;
            this.selHino(this.hinos[this.indice], this.indice);
          }

        } else {
          let indice = this.random();
          this.selHino(this.hinos[indice], indice);
        }
      },

      backHino() {
        if ((this.indice - 1) < 0) {
          this.selHino(this.hinos[this.hinos.length - 1], this.hinos.length - 1);

        } else {
          this.indice--;
          this.selHino(this.hinos[this.indice], this.indice);
        }
      },

      formatTime(time) {
        let min = Math.floor(time / 60);
        let sec = Math.floor(time % 60);
        return min + ':' + ((sec < 10) ? ('0' + sec) : sec);
      },

      random() {
        return Math.floor((Math.random() * this.hinos.length - 1) + 1);
      },

      ameiHino() {
        this.verificarLogin();

        if (this.logado) {
          console.log(this.token);
          this.$http.get(base_url + 'salvar/hino/' + this.selecionado.id_hymn + '/' + this.token)
            .then(() => {
              this.amei = !this.amei;
            });

        } else {
          this.$refs.login.showModal(1);
        }
      },

      verificarLogin() {
        if (sessionStorage.getItem('usuario') !== null) {
          this.user = JSON.parse(sessionStorage.getItem('usuario'));
          this.token = this.user.chave;
          this.logado = true;

        } else {
          this.token = token;
        }
      }
    },
    mounted() {
      this.player = document.getElementById("audioplayer");

      this.verificarLogin();
      this.getHinos();
    }
  }
</script>

<style scoped>
  .controlers {
    margin-bottom: 20px;
    padding: 0 15%;
  }

  .subcontroller {
    margin: 13px 0 16px;
  }

  i {
    color: white;
  }

  .subcontroller i {
    font-size: 1.4em;
  }

  .active {
    color: #00cc7c;
    font-weight: bold;;
  }

  .active i {
    color: #00cc7c;
  }

  .sublegenda {
    display: block;
  }
</style>
