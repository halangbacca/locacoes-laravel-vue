<template>
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col" v-for="(t, key) in titulos" :key="key">{{ t.titulo }}</th>
            <th v-if="visualizar.visivel || atualizar.visivel || remover.visivel" scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(obj, chave) in dadosFiltrados" :key="chave">
            <td v-for="(valor, chaveValor) in obj" :key="chaveValor">
                <span v-if="titulos[chaveValor].tipo === 'imagem'">
                    <img :src="'/storage/' + valor" width="30" height="30" alt="Imagem da marca">
                </span>
                <span v-if="titulos[chaveValor].tipo === 'text'">{{ valor }}</span>
                <span v-if="titulos[chaveValor].tipo === 'data'">{{ valor | formataDataTempoGlobal }}</span>
            </td>
            <td v-if="visualizar.visivel || atualizar.visivel || remover.visivel">
                <button v-if="visualizar.visivel" class="btn btn-outline-primary btn-sm"
                        :data-bs-toggle="visualizar.dataToggle" :data-bs-target="visualizar.dataTarget"
                        @click="setStore(obj)">Visualizar
                </button>
                <button v-if="atualizar.visivel" class="btn btn-outline-primary btn-sm"
                        :data-bs-toggle="atualizar.dataToggle"
                        :data-bs-target="atualizar.dataTarget"
                        @click="setStore(obj)">Atualizar
                </button>
                <button v-if="remover.visivel" class="btn btn-outline-danger btn-sm"
                        :data-bs-toggle="remover.dataToggle"
                        :data-bs-target="remover.dataTarget"
                        @click="setStore(obj)">Remover
                </button>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: ['dados', 'titulos', 'visualizar', 'atualizar', 'remover'],
    computed: {
        dadosFiltrados() {
            let campos = Object.keys(this.titulos)
            let dadosFiltrados = []

            this.dados.map((item) => {
                let itemFiltrado = {}
                campos.forEach((campo) => {
                    itemFiltrado[campo] = item[campo]
                })
                dadosFiltrados.push(itemFiltrado);
            })
            return dadosFiltrados;
        }
    },
    methods: {
        setStore(obj) {
            this.$store.state.detalhesTransacao.status = '';
            this.$store.state.detalhesTransacao.mensagem = '';
            this.$store.state.detalhesTransacao.dados = '';
            this.$store.state.item = obj;
        }
    }
}
</script>
