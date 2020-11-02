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
                Campo para ordenação
                <select name="campoOrdenacao" v-model="campoOrdenacao" id="campoOrdenacao">
                    <option value="nome" selected>Nome</option>
                    <option value="abreviacao">Abreviação</option>
                    <option value="dataCriacao">Data de criação</option>
                    <option value="dataAtualizacao">Data de atualização</option>
                </select>
            </div>
            
            <div>
                Tipo da ordenação
                <select name="tipoOrdenacao" v-model="tipoOrdenacao" id="tipoOrdenacao">
                    <option value="ASC" selected>Ascendente</option>
                    <option value="DESC">Descendente</option>
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
                    <th>Data de criação</th>
                    <th>Data de atualização</th>
                </tr>
            </thead>
            <tbody v-for="estado in estados" :key="estado.id">
                <tr>
                    <td scope="row">{{estado.nome}}</td>
                    <td>{{estado.abreviacao}}</td>
                    <td>{{estado.dataCriacao}}</td>
                    <td>{{estado.dataAtualizacao}}</td>
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
    cidades: [],
    campoOrdenacao: '',
    tipoOrdenacao: ''
  },
  methods: {
      buscarEstado: function () {
        axios.get('/estado?search='+this.searchString + '&sortField=' + this.campoOrdenacao + '&sortType=' + this.tipoOrdenacao, {
            headers: {
                'X-Api-Key': 'chave_segura_da_api'
            }
        })
        .then((response) => {
            this.estados = response.data;
        })
        .catch((error) => {
            console.log(error);
        })
      }
  }
})
</script>
</html>