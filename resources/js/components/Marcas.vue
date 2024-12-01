<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <!-- Início do card de busca -->
                <card-component titulo="Busca de marcas">
                    <template v-slot:conteudo>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp"
                                                           texto-ajuda="Opcional. Informe o ID do registro">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp"
                                           placeholder="Digite o ID da marca" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class="col-md-6">
                                <input-container-component titulo="Nome" id="inputNome" id-help="nomeHelp"
                                                           texto-ajuda="Opcional. Informe o nome da marca">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp"
                                           placeholder="Digite o nome da marca" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>
                <!-- Término do card de busca -->

                <!-- Início do card de listagem -->
                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo>
                        <table-component
                            :visualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modalVisualizacaoMarca'}"
                            :remover="{visivel: true, dataToggle: 'modal', dataTarget: '#modalRemocaoMarca'}"
                            :atualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modalAtualizacaoMarca'}"
                            :dados="marcas.data"
                            :titulos="{
                                id: {titulo: 'ID', tipo: 'text'},
                                nome: {titulo: 'Nome', tipo: 'text'},
                                imagem: {titulo: 'Imagem', tipo: 'imagem'},
                                created_at: {titulo: 'Data de criação', tipo: 'data'},
                            }"></table-component>
                    </template>
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <pagination-component>
                                    <li v-for="(link, key) in marcas.links" :key="key"
                                        :class="link.active ? 'page-item active' : 'page-item'"
                                        @click="paginacao(link)">
                                        <a class="page-link" v-html="link.label"></a>
                                    </li>
                                </pagination-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalMarca">Adicionar
                                </button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- Término do card de listagem -->
            </div>
        </div>

        <!-- Início do modal de inclusão de marca -->
        <modal-component id="modalMarca" titulo="Adicionar marca">
            <template v-slot:alertas>
                <alert-component titulo='Cadastro realizado com sucesso.' :detalhes="$store.state.detalhesTransacao" tipo="success"
                                 v-if="statusTransacao === 'adicionado'"></alert-component>
                <alert-component titulo='Erro ao tentar cadastrar a marca.' :detalhes="$store.state.detalhesTransacao" tipo="danger"
                                 v-if="statusTransacao === 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome" id="novoNome" id-help="novoNomeHelp"
                                               texto-ajuda="Informe o nome da marca">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp"
                               placeholder="Digite o nome da marca" v-model="nomeMarca">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novaImagem" id-help="novaImagemHelp"
                                               texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control" id="novaImagem" aria-describedby="novaImagemHelp"
                               placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
        <!-- Término do modal de inclusão de marca -->

        <!-- Início do modal de visualização de marca -->
        <modal-component id="modalVisualizacaoMarca" titulo="Visualização da marca">
            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
                <input-container-component titulo="Imagem">
                    <img class="img-fluid" :src="'/storage/' + $store.state.item.imagem" v-if="$store.state.item.imagem"
                         alt="Imagem da marca">
                </input-container-component>
                <input-container-component titulo="Data de criação">
                    <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- Término do modal de visualização de marca -->

        <!-- Início do modal de remoção de marca -->
        <modal-component id="modalRemocaoMarca" titulo="Remoção da marca">
            <template v-slot:alertas>
                <alert-component titulo='Transação realizada com sucesso.' :detalhes="$store.state.detalhesTransacao" tipo="success"
                                 v-if="statusTransacao === 'removido'"></alert-component>
                <alert-component titulo='Erro ao tentar deletar a marca.' :detalhes="$store.state.detalhesTransacao" tipo="danger"
                                 v-if="statusTransacao === 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()">Remover</button>
            </template>
        </modal-component>
        <!-- Término do modal de remoção de marca -->

        <!-- Início do modal de atualização de marca -->
        <modal-component id="modalAtualizacaoMarca" titulo="Atualizar marca">
            <template v-slot:alertas>
                <alert-component titulo='Atualização realizada com sucesso.' :detalhes="$store.state.detalhesTransacao"
                                 tipo="success"
                                 v-if="statusTransacao === 'atualizado'"></alert-component>
                <alert-component titulo='Erro ao tentar atualizar a marca.' :detalhes="$store.state.detalhesTransacao" tipo="danger"
                                 v-if="statusTransacao === 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome" id="atualizarNome" id-help="atualizarNomeHelp"
                                               texto-ajuda="Informe o nome da marca">
                        <input type="text" class="form-control" id="atualizarNome" aria-describedby="atualizarNomeHelp"
                               placeholder="Digite o nome da marca" v-model="$store.state.item.nome">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="atualizarImagem" id-help="atualizarImagemHelp"
                                               texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control" id="atualizarImagem"
                               aria-describedby="atualizarImagemHelp"
                               placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
        </modal-component>
        <!-- Término do modal de atualização de marca -->

    </div>
</template>

<script>
export default {
    data() {
        return {
            urlBase: 'http://localhost:8000/api/v1/marca',
            urlPaginacao: '',
            urlFiltro: '',
            nomeMarca: '',
            arquivoImagem: [],
            statusTransacao: '',
            marcas: {
                data: []
            },
            busca: {
                id: '',
                nome: ''
            }
        }
    },
    computed: {
        token() {
            return localStorage.getItem('token');
        }
    },
    methods: {
        paginacao(link) {
            if (link.url) {
                this.urlPaginacao = link.url.split('?')[1];
                this.carregarLista();
            }
        },

        carregarImagem(event) {
            this.arquivoImagem = event.target.files;
        },

        carregarLista() {
            let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro;

            let config = {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + this.token
                }
            }

            axios.get(url, config).then(response => {
                this.marcas = response.data;
                console.log(this.marcas);
            }).catch(errors => {
                console.log(errors);
            });
        },

        pesquisar() {
            let filtro = '';

            for (let chave in this.busca) {
                if (this.busca[chave]) {
                    if (filtro !== '') {
                        filtro += ';'
                    }
                    filtro += chave + ':like:' + this.busca[chave];
                }
            }
            if (filtro !== '') {
                this.urlPaginacao = 'page=1';
                this.urlFiltro = "&filtro=" + filtro;
            } else {
                this.urlFiltro = '';
            }
            this.carregarLista();
        },

        remover() {
            let confirm = window.confirm('Tem certeza que deseja remover essa marca?');
            if (confirm) {
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + this.token
                    }
                }
                axios.delete(this.urlBase + '/' + this.$store.state.item.id, config)
                    .then(response => {
                        this.statusTransacao = 'removido';
                        this.$store.state.detalhesTransacao = {
                            mensagem: 'Marca removida com sucesso.'
                        }
                        console.log(response);
                        this.carregarLista();
                    }).catch(errors => {
                    this.statusTransacao = 'erro';
                    this.$store.state.detalhesTransacao = {
                        mensagem: errors.response.data.message,
                        erros: errors.response.data.errors
                    };
                    console.log(errors.response)
                });
            }
        },

        atualizar() {
            let formData = new FormData();
            formData.append('_method', 'patch');
            formData.append('nome', this.$store.state.item.nome);

            if (this.arquivoImagem[0]) {
                formData.append('imagem', this.arquivoImagem[0]);
            }

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + this.token
                }
            }
            axios.post(this.urlBase + '/' + this.$store.state.item.id, formData, config)
                .then(response => {
                    this.statusTransacao = 'atualizado';
                    this.$store.state.detalhesTransacao = {
                        mensagem: 'Marca atualizada com sucesso.'
                    }
                    console.log(response);
                    atualizarImagem.value = ''
                    this.carregarLista();
                }).catch(errors => {
                this.statusTransacao = 'erro';
                this.$store.state.detalhesTransacao = {
                    mensagem: errors.response.data.message,
                    dados: errors.response.data.errors
                };
                console.log(this.$store.state.detalhesTransacao.erros)
            });
        },

        salvar() {
            let formData = new FormData();
            formData.append('nome', this.nomeMarca);
            formData.append('imagem', this.arquivoImagem[0]);

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + this.token
                }
            }
            axios.post(this.urlBase, formData, config)
                .then(response => {
                    this.statusTransacao = 'adicionado';
                    this.$store.state.detalhesTransacao = {
                        mensagem: 'ID do registro: ' + response.data.id,
                    }
                    console.log(response);
                    this.carregarLista();
                }).catch(errors => {
                this.statusTransacao = 'erro';
                this.$store.state.detalhesTransacao = {
                    mensagem: errors.response.data.message,
                    erros: errors.response.data.errors
                };
                console.log(errors.response)
            });
        }
    },
    mounted() {
        this.carregarLista();
    }
}
</script>
