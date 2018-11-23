<template>
    <div>
      <nav class="top cell-row card black" style="padding: 16px 20px">
        <div class="cell" style="width: 50%">
          <img class="image" src="../assets/icons/menu.svg" style="width: 20px; height: 20px" />
        </div>
        <div class="cell right-align" style="width: 50%">
          <img class="image" src="../assets/icons/more.svg" style="width: 20px; height: 20px" />
        </div>
      </nav>
      <div class="center" style="padding-top: 70px">
        <div style="padding-bottom: 180px">
          <div class="padding" v-for="(h, index) in hinos">
            <div class="left-align" :class="{'active':h===selecionado}" @click="selHino(h,index)" style="padding-left: 12px">
              {{h.nome}} <br>
              <span class="tiny">Tocata 2018</span>
            </div>
          </div>
        </div>
        <div class="bottom card black" style="padding-top: 20px">
          <div class="cell-row cell-middle controlers">
            <div class="cell" style="width: 33%">
              <i class="fa fa-step-backward fa-2x" @click="backHino"></i>
            </div>
            <div class="cell" style="width: 33%">
              <i class="fa fa-3x" :class="paused ? 'fa-play' : 'fa-pause'" @click="tooglePlay"></i>
            </div>
            <div class="cell" style="width: 33%">
              <i class="fa fa-step-forward fa-2x" @click="nextHino"></i>
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
              <i class="fa fa-heart-o"></i>
              <span class="tiny sublegenda">Amei</span>
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
                url: 'https://www.w3schools.com/tags/horse.ogg'
              },
              {
                id: 2,
                nome: 'Jovens amai o Conselho',
                url: 'https://www.canticosccb.com.br/system/files/37962/original/272_-_Dia_Feliz_-_Irma_Maria_Tossani_.mp3?1363955788'
              },
              {
                id: 3,
                nome: 'Eu sou um Cordeirinho',
                url: 'https://www.canticosccb.com.br/system/files/37962/original/272_-_Dia_Feliz_-_Irma_Maria_Tossani_.mp3?1363955788'
              },
              {
                id: 4,
                nome: 'Somos Joias Preciosas',
                url: 'https://www.canticosccb.com.br/system/files/37962/original/272_-_Dia_Feliz_-_Irma_Maria_Tossani_.mp3?1363955788'
              },
              {
                id: 5,
                nome: 'Brilha mais e mais',
                url: 'https://www.canticosccb.com.br/system/files/37962/original/272_-_Dia_Feliz_-_Irma_Maria_Tossani_.mp3?1363955788'
              },
              {
                id: 6,
                nome: 'Sou Criança Senhor',
                url: 'https://www.canticosccb.com.br/system/files/37962/original/272_-_Dia_Feliz_-_Irma_Maria_Tossani_.mp3?1363955788'
              }
            ],
            selecionado: "",
            player: '',
            paused: true,
            progress: '0',
            currentTime: "0:00",
            totalTime: "0:00",
            aleatorio: false,
            automatico: true,
            indice: 0
          }
        },
        methods: {
            selHino(h,indice) {
                this.selecionado = h;
                this.player.setAttribute("src",h.url);
                this.indice = indice;
                this.tooglePlay();
            },

            tooglePlay() {
              if(this.player.paused) {

                if (this.selecionado === "") {
                  this.selHino(this.hinos[0],0);
                  return;
                }

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

                this.paused = false;
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
                  this.selHino(this.hinos[0],0);

                }  else {
                  this.indice++;
                  this.selHino(this.hinos[this.indice],this.indice);
                }

              } else {
                let indice = this.random();
                this.selHino(this.hinos[indice],indice);
              }
            },

            backHino() {
              if ((this.indice - 1) < 0) {
                this.selHino(this.hinos[this.hinos.length - 1],this.hinos.length - 1);

              } else {
                this.indice--;
                this.selHino(this.hinos[this.indice],this.indice);
              }
            },

            formatTime(time) {
              let min = Math.floor(time / 60);
              let sec = Math.floor(time % 60);
              return min + ':' + ((sec<10) ? ('0' + sec) : sec);
            },

            random() {
              return Math.floor((Math.random() * this.hinos.length - 1) + 1);
            }
        },
        mounted() {
          document.getElementById("body").classList.add("black");
          this.player = document.getElementById("elias");
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
  .active i{
    color: #00cc7c;
  }
  .sublegenda {
    display: block;
  }
</style>
