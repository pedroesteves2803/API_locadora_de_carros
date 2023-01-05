<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- inicio card de busca -->
                <card-component titulo="Busca de Carros">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputID" id-help="idHelp" texto-ajuda="Opcional. Informe o ID do carro">
                                    <input type="number" class="form-control" id="inputID" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Placa" id="inputPlaca" id-help="idHelp" texto-ajuda="Opcional. Informe a placa">
                                    <input type="text" class="form-control" id="inputPlaca" aria-describedby="inputPlaca" placeholder="Placa" v-model="busca.placa">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>

                </card-component>
                <!-- fim card de busca -->

                <!-- inicio card de busca -->
                <card-component titulo="Relação de carros">
                    <template v-slot:conteudo>
                        <table-component
                            :dados="carros.data"
                            :visualizar= "{ visivel: true, dataToggle: 'modal', dataTarget: '#modalCarroVisualizar'}"
                            :atualizar= "{ visivel: true, dataToggle: 'modal', dataTarget: '#modalCarroAtualizar'}"
                            :remover= "{ visivel: true, dataToggle: 'modal', dataTarget: '#modalCarroRemover'}"
                            :titulos="{
                                id: {titulo: 'ID' , tipo: 'text'},
                                placa: {titulo: 'Placa' , tipo: 'text'},
                                disponivel: {titulo: 'Disponivel' , tipo: 'disponivel'},
                                km: {titulo: 'KM' , tipo: 'text'},
                                modelo: {titulo: 'Modelo' , tipo: 'modelo'},
                                created_at: {titulo: 'Data de criação' , tipo: 'data'}
                            }"
                        ></table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in carros.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalCarro">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- fim card de busca -->
            </div>


        </div>

        <modal-component id="modalCarro" titulo="Adicionar carro">

            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Sucesso ao tentar cadastrar a marca" v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Modelo do carro" id="modeloCarro" id-help="modeloCarroHelp" texto-ajuda="Informe o modelo do carro">
                        <select class="form-select" id="modeloCarro" id-help="modeloCarroHelp" v-model="modeloCarro">
                            <option selected="selected" disabled="disabled" value="selected">Selecione</option>
                            <option v-for="modelo in modelos.data" v-bind:key="modelo.id" v-bind:value="modelo.id">
                                {{ modelo.nome }}
                            </option>
                        </select>
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Placa do carro" id="placaCarro" id-help="placaCarroHelp" texto-ajuda="Informe a placa do carro">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="placaCarroHelp" placeholder="Placa do carro" v-model="placaCarro">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Disponibilidade do carro" id="disponibilidadeCarro" id-help="disponibilidadeCarroHelp" texto-ajuda="Informe se o carro está disponivel">
                        <select class="form-select" id="disponibilidadeCarro" id-help="disponibilidadeCarroHelp" v-model="disponibilidadeCarro">
                            <option selected="selected" disabled="disabled" value="selected">Selecione</option>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="KM do carro" id="kmCarro" id-help="kmCarroHelp" texto-ajuda="Informe o KM do carro">
                        <input type="text" class="form-control" id="kmCarro" aria-describedby="kmCarroHelp" placeholder="KM do carro" v-model="kmCarro">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>

        <modal-component id="modalCarroVisualizar" titulo="Adicionar carro">

            <template v-slot:alertas>
            </template>

            <template v-slot:conteudo>
                <input-container-component titulo="Modelo do carro">
                    <input type="text" class="form-control" v-if="$store.state.item.modelo" :value="$store.state.item.modelo.nome" disabled >
                </input-container-component>

                <input-container-component titulo="Placa do marca">
                    <input type="text" class="form-control" :value="$store.state.item.placa" disabled>
                </input-container-component>

                <input-container-component titulo="Disponivel">
                    <input type="text" class="form-control" :value="$store.state.item.disponivel | formataDisponibilidade" disabled>
                </input-container-component>

                <input-container-component titulo="KM do carro">
                    <input type="text" class="form-control" :value="$store.state.item.km" disabled>
                </input-container-component>

                <input-container-component titulo="Data de criaçao">
                    <input type="text" class="form-control" :value="$store.state.item.created_at | formataDataTempo" disabled>
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>

        <modal-component id="modalCarroRemover" titulo="Remover marca">

            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes=$store.state.transacao v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes=$store.state.transacao v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Modelo">
                    <input type="text" class="form-control" v-if="$store.state.item.modelo" :value="$store.state.item.modelo.nome" disabled>
                </input-container-component>

                <input-container-component titulo="Placa">
                    <input type="text" class="form-control" :value="$store.state.item.placa" disabled>
                </input-container-component>

            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
            </template>
        </modal-component>

        <modal-component id="modalCarroAtualizar" titulo="Atualizar carro">

            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes=$store.state.transacao v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes=$store.state.transacao v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Modelo do carro" id="modeloCarro" id-help="modeloCarroHelp" texto-ajuda="Informe o modelo do carro">
                        <select class="form-select" id="modeloCarro" id-help="modeloCarroHelp" v-if="$store.state.item.modelo" v-model="$store.state.item.modelo.id">
                            <option selected="selected" disabled="disabled" value="selected">Selecione</option>
                            <option v-for="modelo in modelos.data" v-bind:key="modelo.id" v-bind:value="modelo.id">
                                {{ modelo.nome }}
                            </option>
                        </select>
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Placa do carro" id="placaCarro" id-help="placaCarroHelp" texto-ajuda="Informe a placa do carro">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="placaCarroHelp" placeholder="Placa do carro" v-model="$store.state.item.placa">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Disponibilidade do carro" id="disponibilidadeCarro" id-help="disponibilidadeCarroHelp" texto-ajuda="Informe se o carro está disponivel">
                        <select class="form-select" id="disponibilidadeCarro" id-help="disponibilidadeCarroHelp" v-model="$store.state.item.disponivel">
                            <option selected="selected" disabled="disabled" value="selected">Selecione</option>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="KM do carro" id="kmCarro" id-help="kmCarroHelp" texto-ajuda="Informe o KM do carro">
                        <input type="text" class="form-control" id="kmCarro" aria-describedby="kmCarroHelp" placeholder="KM do carro" v-model="$store.state.item.km">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
        </modal-component>


    </div>

</template>

<script>
import Alert from './Alert.vue';
export default {
  components: { Alert },
        data() {
            return {
                urlBase: "http://127.0.0.1:8000/api/v1/carro",
                urlBaseModelos: "http://127.0.0.1:8000/api/v1/modelo",
                urlPaginacao: '',
                urlFiltro: '',
                modeloCarro: '',
                placaCarro: '',
                disponibilidadeCarro: '',
                kmCarro: '',
                transacaoStatus: '',
                transacaoDetalhes: {},
                carros: { data: [] },
                modelos: { data: [] },
                busca: {id: '', placa: ''},
            }
        },
        methods: {
            carregarModelos() {
                let url = this.urlBaseModelos + '?' + this.urlPaginacao + '?atributos=id,nome&atributos_marca=id,nome';

                axios.get(url)
                    .then(response => {
                        this.modelos = response.data;

                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            atualizar() {

                let formData = new FormData();
                formData.append('_method', 'patch');
                formData.append("modelo_id", this.$store.state.item.modelo.id);
                formData.append("placa", this.$store.state.item.placa);
                formData.append("disponivel", this.$store.state.item.disponivel);
                formData.append("km", this.$store.state.item.km);

                let url = this.urlBase + '/' + this.$store.state.item.id;

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                }

                axios.post(url, formData, config)
                    .then(response => {
                        this.$store.state.transacao.status = 'sucesso';
                        this.$store.state.transacao.mensagem = 'Registro de carro atualizado com sucesso!';
                        this.carregarLista();
                    })
                    .catch(error => {
                        console.log(error);
                        this.$store.state.transacao.status = 'erro';
                        this.$store.state.transacao.mensagem = error.response.data.message;
                        this.$store.state.transacao.dados = error.response.data.errors;
                    });
            },
            remover(){
                let confirmacao = confirm('Tem certeza que deseja remover esse registro?');

                if(!confirmacao){
                     return false;
                }

                let url = this.urlBase + '/' + this.$store.state.item.id;

                let formData = new FormData();
                formData.append('_method', 'delete');

                axios.post(url, formData)
                    .then(response => {
                        this.$store.state.transacao.status = 'sucesso';
                        this.$store.state.transacao.mensagem = response.data.msg;
                        this.carregarLista();
                    })
                    .catch(error => {
                        this.$store.state.transacao.status = 'erro';
                        this.$store.state.transacao.mensagem = error.response.data.erro;
                    });
            },
            pesquisar() {
                let filtro = '';

                for(let chave in this.busca){
                    if(this.busca[chave]){
                        if(filtro != ''){
                            filtro = filtro + ";";
                        }
                        filtro += chave + ':like:' + this.busca[chave]+'%';
                    }
                }

                if(filtro != ''){
                    this.urlPaginacao = 'page=1';
                    this.urlFiltro = "&filtro="+filtro;
                }else{
                    this.urlFiltro = '';
                }

                this.carregarLista();
            },
            paginacao(l) {
                if(l.url){
                    this.urlPaginacao = l.url.split('?')[1];
                    this.carregarLista();
                }
            },
            carregarLista(){

                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro;

                axios.get(url)
                    .then(response =>{
                        this.carros = response.data;
                    })
                    .catch(error =>{
                        console.log(error);
                    });
            },
            carregarImagem(e){
                this.arquivoImagem = e.target.files;
            },
            salvar() {
                let formData = new FormData();
                formData.append("modelo_id", this.modeloCarro);
                formData.append("placa", this.placaCarro);
                formData.append("disponivel", this.disponibilidadeCarro);
                formData.append("km", this.kmCarro);

                let config = {
                    headers: {
                        'Content-type': 'multipart/form-data',
                    }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        this.transacaoStatus = 'adicionado';
                        this.transacaoDetalhes = {
                            mensagem: 'ID do registro:' + response.data.id
                        }

                        this.carregarLista();
                    })
                    .catch(error => {
                        console.log(error);
                        this.transacaoStatus = 'erro';
                        this.transacaoDetalhes = {
                            mensagem: error.response.data.message,
                            dados: error.response.data.errors
                        }
                    });
            }

        },
        mounted() {
            this.carregarModelos();
            this.carregarLista();
        }
    }
</script>
