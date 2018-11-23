<template>
    <div>
      <nav class="cell-row card" style="padding: 16px 20px">
        <div class="cell" style="width: 50%">
          <img class="image" src="../assets/icons/menu.svg" style="width: 20px; height: 20px" />
        </div>
        <div class="cell right-align" style="width: 50%">
          <img class="image" src="../assets/icons/more.svg" style="width: 20px; height: 20px" />
        </div>
      </nav>
      <div class="center" style="margin-top: 20px">
        <div style="height: 60vh; overflow: auto">
          <div class="padding" v-for="h in hinos">
            <div class="left-align" :class="{'active':h===selecionado}" @click="selHino(h)" style="padding-left: 12px">
              {{h.nome}} <br>
              <span class="tiny">Tocata 2018</span>
            </div>
          </div>
        </div>
        <div class="bottom card black" style="padding-top: 20px">
          <div class="cell-row cell-middle controlers">
            <div class="cell" style="width: 33%">
              <i class="fa fa-step-backward fa-2x"></i>
            </div>
            <div class="cell" style="width: 33%">
              <i class="fa fa-3x" :class="paused ? 'fa-play' : 'fa-pause'" @click="tooglePlay"></i>
            </div>
            <div class="cell" style="width: 33%">
              <i class="fa fa-step-forward fa-2x"></i>
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
          <div class="cell-row cell-middle sub-controller">
            <div class="cell" style="width: 25%">
              <i class="fa fa-random sub-controllers"></i>
            </div>
            <div class="cell" style="width: 25%">
              <i class="fa fa-cloud-download sub-controllers"></i>
            </div>
            <div class="cell" style="width: 25%">
              <i class="fa fa-heart-o sub-controllers"></i>
            </div>
            <div class="cell" style="width: 25%">
              <i class="fa fa-share sub-controllers"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
        name: "Home",
        data() {
          return {
            hinos: [
              {
                id: 1,
                nome: 'Cidadão dos Céus',
                url: 'https://www.canticosccb.com.br/system/files/37962/original/272_-_Dia_Feliz_-_Irma_Maria_Tossani_.mp3?1363955788'
              },
              {
                id: 2,
                nome: 'Jovens amai o Conselho',
                url: 'http://localhost/music.mp3'
              },
              {
                id: 3,
                nome: 'Eu sou um Cordeirinho',
                url: 'http://localhost/music.mp3'
              },
              {
                id: 4,
                nome: 'Somos Joias Preciosas',
                url: 'http://localhost/music.mp3'
              },
              {
                id: 5,
                nome: 'Brilha mais e mais',
                url: 'http://localhost/music.mp3'
              },
              {
                id: 6,
                nome: 'Sou Criança Senhor',
                url: 'http://localhost/music.mp3'
              }
            ],
            selecionado: {},
            player: '',
            paused: true,
            progress: '0',
            currentTime: "0:00",
            totalTime: "0:00"
          }
        },
        methods: {
            selHino(h) {
                this.selecionado = h;
                this.player.setAttribute("src",h.url);

                this.tooglePlay();
            },

            tooglePlay() {
              if(this.player.paused) {
                this.player.play();
                this.player.addEventListener('timeupdate', this.updateProgress);

                this.player.addEventListener('loadedmetadata', res => {
                  this.totalTime = this.formatTime(this.player.duration);
                });

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

            formatTime(time) {
              let min = Math.floor(time / 60);
              let sec = Math.floor(time % 60);
              return min + ':' + ((sec<10) ? ('0' + sec) : sec);
          }
        },
        mounted() {
          this.player = document.getElementById("elias");
          console.log(document.getElementById("elias"));
          document.getElementById("body").classList.add("black");
        }
    }
</script>

<style scoped>
  .controlers {
    margin-bottom: 25px;
    padding: 0 10%;
  }
  .informations {
    margin-top: 3vh;
  }
  .sub-controllers {
    margin: 13px 0 26px;
  }
  i {
    color: white;
  }
  i.sub-controllers {
    font-size: 1.4em;
  }
  .active {
    color: #00cc7c;
    font-weight: bold;
  }
</style>
