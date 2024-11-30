<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body" @submit.prevent="login($event)">
                        <form method="POST" action="">
                            <input type="hidden" name="_token" :value="token_csrf">
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">E-mail</label>
                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control"
                                           name="email" value="" required autocomplete="email"
                                           autofocus
                                           v-model="email">
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>
                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control" name="password"
                                           required
                                           autocomplete="current-password"
                                           v-model="password">

                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                        Mantenha-me conectado
                                        <label class="form-check-label" for="remember">

                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Login</button>
                                    <a class="btn btn-link" href="">Esqueci a senha</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['token_csrf'],
    data() {
        return {
            email: '',
            password: '',
        }
    },
    methods: {
        login(event) {
            let url = 'http://localhost:8000/api/login'
            let configuracao = {
                method: 'POST',
                body: new FormData(event.target),
            }

            fetch(url, configuracao)
                .then(response => response.json())
                .then(data => {
                    if (data.token) {
                        localStorage.setItem('token', data.token);
                    }
                    event.target.submit();
                })
        }
    }
}
</script>
