<template>
    <div :class="'alert-' + tipo" class="alert" role="alert">
        {{ titulo }}
        <hr>
        <p>{{ detalhes.mensagem }}</p>
        <ul v-if="detalhes.dados">
            <li v-for="(error, key) in detalhes.dados" :key="key">{{ error[0] }}</li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ['titulo', 'detalhes', 'tipo']
}

</script>
