<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Маршруты обработки заявок клиента</h3>
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
                        <td>{{ route.name  }}</td>
                        <td>{{ route.value  }}</td>
                        <td>{{ route.description  }}</td>
                        <td>{{ route.in_use  }}</td>
                        <td>
                            <router-link :to="{name: 'route-update', params: {id: route.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <button class="btn btn-danger" @click.prevent = "deleteRoute(route.id)">Удалить</button>
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
                routes: []
            }
        },
        mounted() {
            let token = localStorage.getItem('jwt')

            this.axios.defaults.headers.common['Content-Type'] = 'application/json'
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            
            let uri = '/api/routes';
            this.axios.get(uri)
            	.then((response) => {
                	this.routes = response.data.data;
                })
                .catch(e => {
                	//console.log(e);
                    if(e == 'Error: Request failed with status code 401') {
                        if (localStorage.getItem('jwt')) {
                            localStorage.removeItem('jwt');
                            this.$router.push({name: 'login'});
                        }
                        //swal('Ошибка аутентификации', "Ползователь не зарегистрирован", "error");
                    }
                    else {
                        swal('Ошибка', "Внутренняя ошибка сервера", "error");
                    }
                });
        },
        methods: {
            deleteRoute(id) {
                let uri = `/api/routes/${id}`;
                if (confirm("Do you really want to delete it?")) {
                    this.axios.delete(uri)
                        .then((response) => {
                            if(response.data.data) {
                                this.routes.splice(this.routes.indexOf(id), 1);
                            }
                            else {
                                swal("Удаление задачи", "Что то пошло не так...", "error");
                            }
                        })
                        .catch(e => {
                            swal('Ошибка', "Внутренняя ошибка сервера", "error");
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