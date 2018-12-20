<template>
  <div class="bottom card black" style="padding-top: 24px; border-top: 1px solid rgba(0, 0, 0, 0.25)">

    <div class="cell-row controlers">
      <div class="cell cell-middle" style="width: 20%" :class="automatico ? 'active' : ''" @click="automatico = !automatico">
        <i class="fa fa-refresh"></i>
      </div>
      <div class="cell cell-middle" style="width: 20%" @click="backHino">
        <i class="fa fa-step-backward" style="font-size: 1.5em"></i>
      </div>
      <div class="cell cell-middle" style="width: 20%" @click="tooglePlay()">
        <i class="fa" :class="paused ? 'fa-play' : 'fa-pause'" style="font-size: 3em" v-show="!load"></i>
        <i class="fa fa-spinner fa-pulse" style="font-size: 3em" v-show="load"></i>
      </div>
      <div class="cell cell-middle" style="width: 20%" @click="nextHino">
        <i class="fa fa-step-forward" style="font-size: 1.5em"></i>
      </div>
      <div class="cell cell-middle" style="width: 20%" :class="aleatorio ? 'active' : ''" @click="aleatorio = !aleatorio">
        <i class="fa fa-random"></i>
      </div>
    </div>

    <div id="timeline">
      <span id="current-time">{{currentTime}}</span>
      <span id="total-time">{{totalTime}}</span>
      <div id="slider" class="slider" data-direction="horizontal" @click="rewind">
        <div class="progress" :style="{'width':progress}">
          <div class="pin" id="progress-pin"></div>
        </div>
      </div>
    </div>

    <audio id="audioplayer">
      <source src="" type="audio/mpeg">
    </audio>
  </div>
</template>

<script>
    export default {
      name: "player",
      props: {
        token: "",
        hinos: "",
      },
      data() {
        return {
          selecionado: '',
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
        selHino(h, indice) {
          this.load = true;
          this.progress = "0%";
          this.$http.get(base_url + 'hino/' + h.id_hymn + '/' + this.token)
            .then(response => {
              this.selecionado = h;
              this.amei = response.data.favorito;
              this.player.setAttribute("src", h.url);
              this.indice = indice;

              this.$router.push("/reproducao/" + this.$route.params.id + "/" + h.id_hymn);
            });
        },

        tooglePlay() {

          if (this.selecionado === "") {
            this.selHino(this.hinos[0], 0);
            return;
          }

          if (this.player.paused) {
            this.player.play();
            this.paused = false;

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
          this.player.pause();
          this.load = true;

          let coeficiente = this.getCoeficiente(event.clientX);
          let current = (coeficiente / 100) * this.player.duration;

          this.progress = current;
          this.player.currentTime = current;
          this.tooglePlay(false);

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
            this.$refs.login.showModal(2);
          }
        }
      },

      mounted() {
        this.player = document.getElementById("audioplayer");

        this.player.addEventListener('loadedmetadata', () => {
          this.totalTime = this.formatTime(this.player.duration);
        });

        this.player.addEventListener('timeupdate', this.updateProgress);

        this.player.addEventListener('ended', () => {
          if (this.automatico) {
            this.nextHino();
          } else {
            this.paused = true;
          }
        });

        this.player.addEventListener('canplay', () => {
          this.paused = false;
          this.player.play();
          this.load = false;
        });

      }
    }
</script>

<style>
  .controlers {
    margin-bottom: 10px;
    text-align: center;
  }
  i {
    color: white;
  }
</style>
