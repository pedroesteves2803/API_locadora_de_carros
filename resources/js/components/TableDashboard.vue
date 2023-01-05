<template>
    <div class="card-group mt-3 mb-3 text-light">
        <div class="card list-group-item-primary" v-if="marcasVisivel">
            <div class="card-body">
                <h5 class="card-title">Marcas</h5>
                <ul class="list-group">
                    <li class="list-group-item">Total: {{lista.marcas.total}}</li>
                </ul>
            </div>
        </div>

        <div class="card list-group-item-danger" v-if="carrosVisivel">
            <div class="card-body">
                <h5 class="card-title">Carros</h5>
                <ul class="list-group">
                    <li class="list-group-item">Total: {{lista.carros.total}}</li>
                </ul>
            </div>
        </div>

        <div class="card list-group-item-warning" v-if="modelosVisivel">
            <div class="card-body">
                <h5 class="card-title">Modelos</h5>
                <ul class="list-group">
                    <li class="list-group-item">Total: {{lista.modelos.total}}</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['marcasVisivel', 'carrosVisivel', 'modelosVisivel'],
    data() {
        return {
            urlBase: "http://127.0.0.1:8000/api/v1/",
            urlPaginacao: '',
            urlFiltro: '',
            lista: {marcas: [], carros: [], modelos: []}
        }
    },
    methods: {
        carregarListaMarcas() {
            let url = this.urlBase+'marca';

            axios.get(url)
                .then(response =>{
                    this.lista.marcas = response.data;
                })
                .catch(error =>{
                    console.log(error);
                });
        },
        carregarListaCarros() {
            let url = this.urlBase+'carro';

            axios.get(url)
                .then(response =>{
                    this.lista.carros = response.data;
                })
                .catch(error =>{
                    console.log(error);
                });
        },
        carregarListaModelos() {
            let url = this.urlBase+'modelo';

            axios.get(url)
                .then(response =>{
                    this.lista.modelos = response.data;
                    // console.log('modelos:'+this.lista.modelos)
                })
                .catch(error =>{
                    console.log(error);
                });
        },
    },
    mounted() {
        this.carregarListaMarcas();
        this.carregarListaCarros();
        this.carregarListaModelos();
    }

}
</script>
