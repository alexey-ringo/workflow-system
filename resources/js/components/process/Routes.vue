<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Маршруты движения обращений клиентов</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Маршрут</th>
                        <th>id-маршрута</th>
                        <th>Описание маршрута</th>
                        <th>Статус</th>
                        <th>Редактировать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="route in routes" :key="route.id">
                        <td>{{ route.title  }}</td>
                        <td>{{ route.value  }}</td>
                        <td>{{ route.description  }}</td>
                        <td>{{ route.is_active  }}</td>
                        <td>
                            <router-link :to="{name: 'route-update', params: {id: route.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <!-- <button class="btn btn-danger" @click.prevent = "deleteRoute(route.id)">Удалить</button> -->
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Маршрут</th>
                        <th>id-маршрута</th>
                        <th>Описание маршрута</th>
                        <th>Статус</th>
                        <th>Редактировать</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</template>

<script>
    export default {
        data: function () {
            return {
                routes: [],
                message: ''
            }
        },
        mounted() {
            let token = localStorage.getItem('jwt')

            this.axios.defaults.headers.common['Content-Type'] = 'application/json'
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            
            this.getRoutes();
        },
        methods: {
            getRoutes() {
                let uri = '/api/routes';
                this.axios.get(uri)
            	    .then((response) => {
            	        if(response.data.data) {
                	        this.routes = response.data.data;
            	        }
            	        else if (response.data.message) {
                            this.message = response.data.message;
                            swal("Ошибка", this.message, "error");
                            this.$router.push({name: 'dashboard'});
                        }
                        else {
                            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к списку маршрутов процессов задач", "error");
                            this.$router.push({name: 'dashboard'});
                        }        
                    })
                    .catch(error => {
                        if(error.response) {
                            if(error.response.data.message) {
                                if(error.response.status == 401) {
                                    if (localStorage.getItem('jwt')) {
                                        localStorage.removeItem('jwt');
                                        this.$router.push({name: 'login'});
                                    }
                                }
                                else {
                                    swal('Ошибка - ' + error.response.status, error.response.data.message, "error");
                                    this.$router.push({name: 'dashboard'});
                                }
                            }         
                        }
                        else if(error.request) {
                        }
                        else {
                            swal('Ошибка', "Внутренняя ошибка сервера", "error");
                            this.$router.push({name: 'dashboard'});
                        }
                    });
            },
            deleteRoute(id) {
                let uri = `/api/routes/${id}`;
                if (confirm("Do you really want to delete it?")) {
                    this.axios.delete(uri)
                        .then((response) => {
                            if(response.data.data) {
                                this.routes.splice(this.routes.indexOf(id), 1);
                            }
                            else if (response.data.message) {
                                this.message = response.data.message;
                                swal("Ошибка", this.message, "error");
                            }
                            else {
                                swal("Ошибка", "Нет ответа от сервера при попытке удаления выбранного маршрута", "error");
                            }
                        })
                        .catch(error => {
                            if(error.response) {
                                if(error.response.data.message) {
                                    if(error.response.status == 401) {
                                        if (localStorage.getItem('jwt')) {
                                            localStorage.removeItem('jwt');
                                            this.$router.push({name: 'login'});
                                        }
                                    }
                                    else {
                                        swal('Ошибка - ' + error.response.status, error.response.data.message, "error");
                                    }
                                }         
                            }
                            else if(error.request) {
                            }
                            else {
                                swal('Ошибка', "Внутренняя ошибка сервера", "error");
                            }
                        });
                }
            }
        },
        beforeRouteEnter (to, from, next) { 
            if ( ! localStorage.getItem('jwt')) {
                return next('login')
            }

            next()
        }
    }
    
</script>