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
                                <input-container-component titulo="Nome" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Informe o nome">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="inputNome" placeholder="Placa" v-model="busca.nome">
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
                            :dados="modelos.data"
                            :visualizar= "{ visivel: true, dataToggle: 'modal', dataTarget: '#modalModeloVisualizar'}"
                            :atualizar= "{ visivel: true, dataToggle: 'modal', dataTarget: '#modalModeloAtualizar'}"
                            :remover= "{ visivel: true, dataToggle: 'modal', dataTarget: '#modalModeloRemover'}"
                            :titulos="{
                                id: {titulo: 'ID' , tipo: 'text'},
                                nome: {titulo: 'Nome' , tipo: 'text'},
                                imagem: {titulo: 'Imagem' , tipo: 'imagem'},
                                numero_portas: {titulo: 'Portas' , tipo: 'text'},
                                abs: {titulo: 'ABS' , tipo: 'disponivel'},
                                air_bag: {titulo: 'Airbag' , tipo: 'disponivel'},
                                marca_id: {titulo: 'ID da marca' , tipo: 'text'},
                                lugares: {titulo: 'Lugares' , tipo: 'text'},
                                created_at: {titulo: 'Data de criação' , tipo: 'data'}
                            }"
                        ></table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in modelos.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalModeloAdicionar">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- fim card de busca -->
            </div>

        </div>

        <modal-component id="modalModeloAdicionar" titulo="Adicionar modelo">

            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Sucesso ao tentar cadastrar a marca" v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>

                <div class="form-group">
                    <input-container-component titulo="Nome do modelo" id="NovoNomeModelo" id-help="nomeModeloHelp" texto-ajuda="Informe o nome do modelo">
                        <input type="text" class="form-control" id="NovoNomeModelo" aria-describedby="nomeModeloHelp" placeholder="Nome modelo" v-model="nome">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="NovaImagemModelo" id-help="NovaImagemModeloHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control" id="NovaImagemModelo" aria-describedby="NovaImagemModeloHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Marca" id="marca" id-help="marcaHelp" texto-ajuda="Informe a marca">
                        <select class="form-select" id="marca" id-help="marcaHelp" v-model="marca_id">
                            <option selected="selected" disabled="disabled" value="selected">Selecione</option>
                            <option v-for="marca in marcas.data" v-bind:key="marca.id" v-bind:value="marca.id">
                                {{ marca.nome }}
                            </option>
                        </select>
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Quantidade de portas" id="PortasModelo" id-help="PortasModeloHelp" texto-ajuda="Informe a quantidade de portas">
                        <input type="text" class="form-control" id="PortasModelo" aria-describedby="PortasModeloHelp" placeholder="Quantidade de portas" v-model="portas">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Quantidade de lugares" id="LugaresModelos" id-help="LugaresModelosHelp" texto-ajuda="Informe a quantidade de lugares">
                        <input type="text" class="form-control" id="LugaresModelos" aria-describedby="LugaresModelosHelp" placeholder="Quantidade de lugares" v-model="lugares">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Airbag" id="AirbagModelos" id-help="AirbagModelosHelp" texto-ajuda="Possui airbag">
                        <select class="form-select" id="AirbagModelos" id-help="AirbagModelosHelp" v-model="airbag">
                            <option selected="selected" disabled="disabled" value="selected">Selecione</option>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="ABS" id="absModelos" id-help="absModelosHelp" texto-ajuda="Possui abs">
                        <select class="form-select" id="absModelos" id-help="absModelosHelp" v-model="abs">
                            <option selected="selected" disabled="disabled" value="selected">Selecione</option>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </input-container-component>
                </div>

            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>

        <modal-component id="modalModeloVisualizar" titulo="Visualizar modelo">

            <template v-slot:alertas>
            </template>

            <template v-slot:conteudo>
                <input-container-component titulo="Imagem">
                    <img :src="'storage/'+$store.state.item.imagem" v-if="$store.state.item.imagem">
                </input-container-component>

                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Nome da marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>

                <input-container-component titulo="Airbag">
                    <input type="text" class="form-control" :value="$store.state.item.air_bag | formataDisponibilidade" disabled>
                </input-container-component>

                <input-container-component titulo="ABS">
                    <input type="text" class="form-control" :value="$store.state.item.abs | formataDisponibilidade" disabled>
                </input-container-component>

                <input-container-component titulo="Quantidade de portas">
                    <input type="text" class="form-control" :value="$store.state.item.numero_portas" disabled>
                </input-container-component>

                <input-container-component titulo="Data de criaçao">
                    <input type="text" class="form-control" :value="$store.state.item.created_at | formataDataTempo" disabled>
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>

        <modal-component id="modalModeloRemover" titulo="Remover modelo">

            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes=$store.state.transacao v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes=$store.state.transacao v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Modelo">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>

            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
            </template>
        </modal-component>

        <modal-component id="modalModeloAtualizar" titulo="Atualizar marca">

            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes=$store.state.transacao v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes=$store.state.transacao v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>

                <div class="form-group">
                    <input-container-component titulo="Nome do modelo" id="NovoNomeModelo" id-help="nomeModeloHelp" texto-ajuda="Informe o nome do modelo">
                        <input type="text" class="form-control" id="NovoNomeModelo" aria-describedby="nomeModeloHelp" placeholder="Nome modelo" v-model="$store.state.item.nome">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="NovaImagemModelo" id-help="NovaImagemModeloHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control" id="NovaImagemModelo" aria-describedby="NovaImagemModeloHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Marca" id="marca" id-help="marcaHelp" texto-ajuda="Informe a marca">
                        <select class="form-select" id="marca" id-help="marcaHelp"  v-model="$store.state.item.marca_id">
                            <option selected="selected" disabled="disabled" value="selected">Selecione</option>
                            <option v-for="marca in marcas.data" v-bind:key="marca.id" v-bind:value="marca.id">
                                {{ marca.nome }}
                            </option>
                        </select>
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Quantidade de portas" id="PortasModelo" id-help="PortasModeloHelp" texto-ajuda="Informe a quantidade de portas">
                        <input type="text" class="form-control" id="PortasModelo" aria-describedby="PortasModeloHelp" placeholder="Quantidade de portas" v-model="$store.state.item.numero_portas">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Quantidade de lugares" id="LugaresModelos" id-help="LugaresModelosHelp" texto-ajuda="Informe a quantidade de lugares">
                        <input type="text" class="form-control" id="LugaresModelos" aria-describedby="LugaresModelosHelp" placeholder="Quantidade de lugares" v-model="$store.state.item.lugares">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Airbag" id="AirbagModelos" id-help="AirbagModelosHelp" texto-ajuda="Possui airbag">
                        <select class="form-select" id="AirbagModelos" id-help="AirbagModelosHelp" v-model="$store.state.item.air_bag">
                            <option selected="selected" disabled="disabled" value="selected">Selecione</option>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="ABS" id="absModelos" id-help="absModelosHelp" texto-ajuda="Possui abs">
                        <select class="form-select" id="absModelos" id-help="absModelosHelp" v-model="$store.state.item.abs">
                            <option selected="selected" disabled="disabled" value="selected">Selecione</option>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
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
                urlBase: "http://127.0.0.1:8000/api/v1/modelo",
                urlBaseMarcas: "http://127.0.0.1:8000/api/v1/marca",
                urlPaginacao: '',
                urlFiltro: '',
                nome: '',
                arquivoImagem: '',
                portas: '',
                marca_id: '',
                lugares: '',
                airbag: '',
                abs: '',
                transacaoStatus: '',
                transacaoDetalhes: {},
                modelos: { data: [] },
                marcas: { data: [] },
                busca: {id: '', nome: ''},
            }
        },
        methods: {
            carregarListaMarcas() {
                let url = this.urlBaseMarcas + '?' + this.urlPaginacao + '';

                axios.get(url)
                    .then(response => {
                        this.marcas = response.data;

                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            atualizar() {
                let formData = new FormData();
                formData.append('_method', 'patch');
                formData.append("nome", this.$store.state.item.nome);
                formData.append("marca_id", this.$store.state.item.marca_id);
                formData.append("numero_portas", this.$store.state.item.numero_portas);
                formData.append("lugares", this.$store.state.item.lugares);
                formData.append("air_bag", this.$store.state.item.air_bag);
                formData.append("abs", this.$store.state.item.abs);

                // if(this.arquivoImagem[0]) {
                //     formData.append('imagem', this.arquivoImagem[0]);
                // }

                let url = this.urlBase + '/' + this.$store.state.item.id;

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                }
                console.log(this.$store.state.item, url)

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
                        this.modelos = response.data;
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
                formData.append("nome", this.nome);
                formData.append("imagem", this.arquivoImagem[0]);
                formData.append("marca_id", this.marca_id);
                formData.append("numero_portas", this.portas);
                formData.append("lugares", this.lugares);
                formData.append("air_bag", this.airbag);
                formData.append("abs", this.abs);

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
            this.carregarLista();
            this.carregarListaMarcas();
        }
    }
</script>
