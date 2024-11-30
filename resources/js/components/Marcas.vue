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
                                           placeholder="Digite o ID da marca">
                                </input-container-component>
                            </div>
                            <div class="col-md-6">
                                <input-container-component titulo="Nome" id="inputNome" id-help="nomeHelp"
                                                           texto-ajuda="Opcional. Informe o nome da marca">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp"
                                           placeholder="Digite o nome da marca">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm">Pesquisar</button>
                    </template>
                </card-component>
                <!-- Término do card de busca -->

                <!-- Início do card de listagem -->
                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo>
                        <table-component></table-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalMarca">Adicionar
                        </button>
                    </template>
                </card-component>
                <!-- Término do card de listagem -->
            </div>
        </div>
        <modal-component id="modalMarca" titulo="Adicionar marca">
            <template v-slot:alertas>
                <alert-component titulo='Cadastro realizado com sucesso.' :mensagem="detalhesTransacao" tipo="success"
                                 v-if="statusTransacao === 'adicionado'"></alert-component>
                <alert-component titulo='Erro ao tentar cadastrar a marca.' :mensagem="detalhesTransacao" tipo="danger"
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
    </div>
</template>

<script>
export default {
    data() {
        return {
            'urlBase': 'http://localhost:8000/api/v1/marca',
            'nomeMarca': '',
            'arquivoImagem': [],
            'statusTransacao': '',
            'detalhesTransacao': ''
        }
    },
    computed: {
        token() {
            return localStorage.getItem('token');
        }
    },
    methods: {
        carregarImagem(event) {
            this.arquivoImagem = event.target.files;
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
                    this.detalhesTransacao = response;
                    console.log(response);
                }).catch(errors => {
                this.statusTransacao = 'erro';
                this.detalhesTransacao = errors.response;
                console.log(errors.response)
            });
        }
    }
}
</script>
