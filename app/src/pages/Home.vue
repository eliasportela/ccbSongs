<template>
  <div>
    <c-header/>
    <div style="padding: 65px 8px 20px">
      <div v-for="c in categorias">
        <div class="nome-categoria">{{c.category}}</div>
        <div class="cds">
          <div class="categoria" v-for="cd in c.cds">
            <div class="card padding padding-32 center" @click="selCd(cd)">{{cd.title}}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import CHeader from '../components/Header'

  export default {
    components: {CHeader},
    name: "home",
    data() {
      return {
        categorias: []
      }
    },
    methods: {
      getCategorias() {
        this.$http.get(base_url + 'categorias/' + token)
          .then(response => {
            this.categorias = response.data;

          });
      },
      selCd(cd) {
        this.$router.push("/reproducao/" + cd.id_cd);
      }
    },
    mounted() {
      this.getCategorias();
    }
  }
</script>

<style scoped>
  .nome-categoria {
    padding-left: 10px;
    margin-top: 12px;
  }
  .cds {
    overflow: auto;
    white-space: nowrap;
  }
  .categoria {
    padding: 8px;
    display: inline-table;
  }
  .categoria .card {
    width: 150px;
    white-space: normal;
    height: 150px;
    background-image: url("../assets/img/fundo.jpg");
  }
</style>
