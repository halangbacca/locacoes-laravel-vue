<template>
    <div :class="'alert-' + tipo" class="alert" role="alert">
        {{ titulo }}
        <hr>
        {{ mensagem.data.message }}
        <ul v-if="mensagem.data.errors">
            <li v-for="error in mensagem.data.errors">{{ error[0] }}</li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ['titulo', 'mensagem', 'tipo']
}

</script>
