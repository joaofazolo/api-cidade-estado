<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca estado</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
</head>
<body>
    <div id="app">
        <div class="col-6 offset-3">
            <div class="row">
                <div class="form-group col-6">
                    <label for="campoOrdenacao">Campo para ordenação</label>
                    <select class="form-control" name="campoOrdenacao" v-model="campoOrdenacao" id="campoOrdenacao">
                        <option value="nome" selected>Nome</option>
                        <option value="abreviacao">Abreviação</option>
                        <option value="dataCriacao">Data de criação</option>
                        <option value="dataAtualizacao">Data de atualização</option>
                    </select>
                </div>
                
                <div class="form-group col-6">
                    <label for="campoOrdenacao">Tipo da ordenação</label>
                    <select class="form-control" name="tipoOrdenacao" v-model="tipoOrdenacao" id="tipoOrdenacao">
                        <option value="ASC" selected>Ascendente</option>
                        <option value="DESC">Descendente</option>
                    </select>
                </div>
            </div>
            <div class="input-group mb-3">
                <input v-model="searchString" id="search" type="text" class="form-control" placeholder="Digite parte do nome ou abreviação">
                <div class="input-group-append">
                    <button @click="buscarEstado" class="btn btn-outline-secondary" type="button" id="button-addon2">Buscar</button>
                </div>
            </div>
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
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script>
var app = new Vue({
  el: '#app',
  data: {
    searchString: '',
    estados: [],
    cidades: [],
    campoOrdenacao: 'nome',
    tipoOrdenacao: 'ASC'
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