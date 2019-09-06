<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Процессы обработки клиентских заявок</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Маршрут</th>
                        <th>Процесс</th>
                        <th>Очередность выполнения процесса в маршруте</th>
                        <th>Процесс супервайзера</th>
                        <th>Завершение процессов в маршруте</th>
                        <th>Редактировать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="process in processes" :key="process.id">
                        <td>{{ process.route.name }}</td>
                        <td>{{ process.name  }}</td>
                        <td>{{ process.sequence  }}</td>
                        <td>{{ process.is_super  }}</td>
                        <td>{{ process.is_final  }}</td>
                        <td>
                            <router-link :to="{name: 'process-update', params: {id: process.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <button class="btn btn-danger" @click.prevent = "deleteProcess(process.id)">Удалить</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Маршрут</th>
                        <th>Процесс</th>
                        <th>Очередность выполнения процесса в маршруте</th>
                        <th>Процесс супервайзера</th>
                        <th>Завершение процессов в маршруте</th>
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
                processes: []
            }
        },
        mounted() {
            let token = localStorage.getItem('jwt')

            this.axios.defaults.headers.common['Content-Type'] = 'application/json'
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            
            let uri = '/api/processes';
            this.axios.get(uri)
            	.then((response) => {
                	this.processes = response.data.data;
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
            deleteProcess(id) {
                let uri = `/api/processes/${id}`;
                if (confirm("Do you really want to delete it?")) {
                    this.axios.delete(uri)
                        .then((response) => {
                            if(response.data.data) {
                                this.processes.splice(this.processes.indexOf(id), 1);
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