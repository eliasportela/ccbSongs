<template>
    <div>
      <nav class="cell-row" style="padding: 20px">
        <div class="cell" style="width: 50%">
          <img class="image" src="../assets/icons/menu.svg" style="width: 20px; height: 20px" />
        </div>
        <div class="cell right-align" style="width: 50%">
          <img class="image" src="../assets/icons/more.svg" style="width: 20px; height: 20px" />
        </div>
      </nav>
      <div class="center">
        <div class="informations">
          <div class="">
            <img class="image" src="../assets/logo.png"/>
          </div>
          <h6 class="large">Nome do Hino</h6>
          <span class="small">Cantor</span>
        </div>
        <div class="bottom">
          <div class="cell-row cell-middle controlers">
            <div class="cell" style="width: 33%">
              <i class="fa fa-step-backward fa-2x"></i>
            </div>
            <div class="cell" style="width: 33%">
              <i class="fa fa-play fa-3x"></i>
            </div>
            <div class="cell" style="width: 33%">
              <i class="fa fa-step-forward fa-2x"></i>
            </div>
          </div>
          <div class="margin">
            <div id="timeline">
              <span id="current-time">0:20</span>
              <span id="total-time">3:11</span>
              <div class="slider" data-direction="horizontal">
                <div class="progress" style="width: 40%;">
                  <div class="pin" id="progress-pin" data-method="rewind"></div>
                </div>
              </div>
            </div>
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
        created() {
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
</style>
