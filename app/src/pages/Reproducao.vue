<template>
  <div>
    <c-header/>
    <div class="center" style="padding-top: 70px">
      <div style="padding-bottom: 200px">
        <div class="padding" v-for="(h, index) in hinos">
          <div class="left-align" :class="{'active':h===selecionado}" @click="selHino(h,index)" style="padding-left: 12px">
              {{h.title}} <br>
            <span class="tiny">{{cd.singer}}</span>
          </div>
        </div>
      </div>
      <div class="bottom card black" style="padding-top: 15px">
        <div class="margin-bottom">
          <span class="small">{{selecionado.title}}</span>
        </div>
        <div class="cell-row controlers">
          <div class="cell cell-middle" style="width: 33%">
            <i class="fa fa-step-backward" @click="backHino" style="font-size: 1.5em"></i>
          </div>
          <div class="cell cell-middle" style="width: 33%">
            <i class="fa" :class="paused ? 'fa-play' : 'fa-pause'" @click="tooglePlay" style="font-size: 3em"></i>
          </div>
          <div class="cell cell-middle" style="width: 33%">
            <i class="fa fa-step-forward" @click="nextHino" style="font-size: 1.5em"></i>
          </div>
        </div>
        <div class="margin">
          <div id="timeline">
            <span id="current-time">{{currentTime}}</span>
            <span id="total-time">{{totalTime}}</span>
            <div class="slider" data-direction="horizontal">
              <div class="progress" :style="{'width':progress}">
                <div class="pin" id="progress-pin" data-method="rewind"></div>
              </div>
            </div>
          </div>
          <audio crossorigin id="elias">
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
          <div class="cell" style="width: 20%">
            <i class="fa" :class="amei ? 'fa-heart active' : 'fa-heart-o'" @click="ameiHino"></i>
            <span class="tiny sublegenda">Amei</span>
          </div>
        </div>
      </div>
    </div>

    <login :showModal="modalLogin" />

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
        modalLogin: false,
        cd: [],
        hinos: [],
        selecionado: "",
        player: '',
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

            this.selHino(this.hinos[0],0);

          });
      },

      selHino(h, indice) {

        this.$http.get(base_url + 'hino/' + h.id_hymn + '/' + this.token)
          .then(response => {
            console.log(response.data.favorito);
            this.selecionado = h;
            this.amei = response.data.favorito;
            this.player.setAttribute("src", h.url);
            this.indice = indice;
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

          });

          this.player.addEventListener('timeupdate', this.updateProgress);

          this.player.addEventListener('ended', () => {
            if (this.automatico) {
              this.nextHino();
            } else {
              this.paused = true;
            }
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
        if (this.logado) {
          this.$http.get(base_url + 'curtir/hino/' + 1 + '/' + this.selecionado.id_hymn + '/' + this.token)
            .then(response => {
              this.amei = true;
            });
        }
      }
    },
    mounted() {
      this.player = document.getElementById("elias");

      if (sessionStorage.getItem('usuario') !== null) {
        let dados = JSON.parse(sessionStorage.getItem('usuario'));
        this.token = dados.token;
        this.logado = true;

      } else {
        this.token = token;
      }

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
