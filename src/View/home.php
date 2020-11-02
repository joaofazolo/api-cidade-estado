<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca cidade e estado</title>
    
</head>
<body>
    <div id="app">
        <div>
            <div>
                Ordenação nome
                <select name="ordenacaoNome" id="">
                    <option value="asc">ASC</option>
                    <option value="desc">DESC</option>
                </select>
            </div>
            
            <div>
                Ordenação abreviacao
                <select name="ordenacaoAbreviacao" id="">
                    <option value="asc">ASC</option>
                    <option value="desc">DESC</option>
                </select>
            </div>
        </div>
        <input type="text" v-model="searchString" id="search">
        <button type="button" @click="buscarEstado">Buscar</button>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Abreviação</th>
                </tr>
            </thead>
            <tbody v-for="estado in estados" :key="estado.id">
                <tr>
                    <td scope="row">{{estado.nome}}</td>
                    <td>{{estado.abreviacao}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
var app = new Vue({
  el: '#app',
  data: {
    searchString: '',
    estados: [],
    cidades: []
  },
  methods: {
      buscarEstado: function () {
        axios.get('/estado?search='+this.searchString)
        .then((response) => {
            // handle success
            this.estados = response.data;
        })
        .catch((error) => {
            // handle error
            console.log(error);
        })
        .then(function () {
            // always executed
        });
      }
  }
})
</script>
</html>