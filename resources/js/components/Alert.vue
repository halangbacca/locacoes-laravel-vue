<template>
    <div :class="'alert-' + tipo" class="alert" role="alert">
        {{ titulo }}
        <hr>
        <p>{{ detalhes.mensagem }}</p>
        <ul v-if="detalhes.erros">
            <li v-for="(error, key) in detalhes.errors" :key="key">{{ error[0] }}</li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ['titulo', 'detalhes', 'tipo']
}

</script>
