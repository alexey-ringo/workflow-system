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
                        <th>Норматив времени</th>
                        <th>Статус</th>
                        <th>Процесс супервайзера</th>
                        <th>Завершение процессов в маршруте</th>
                        <th>Редактировать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="process in processes" :key="process.id">
                        <td>{{ process.route.title }}</td>
                        <td>{{ process.title }}</td>
                        <td>{{ process.sequence }}</td>
                        <td>{{ process.deadline }}</td>
                        <td>{{ process.is_active }}</td>
                        <td>{{ process.is_super }}</td>
                        <td>{{ process.is_final }}</td>
                        <td>
                            <router-link :to="{name: 'process-update', params: {id: process.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <!-- <button class="btn btn-danger" @click.prevent = "deleteProcess(process.id)">Удалить</button> -->
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Маршрут</th>
                        <th>Процесс</th>
                        <th>Очередность выполнения процесса в маршруте</th>
                        <th>Норматив времени</th>
                        <th>Статус</th>
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
                processes: [],
                message: ''
            }
        },
        mounted() {
            let token = localStorage.getItem('jwt')

            this.axios.defaults.headers.common['Content-Type'] = 'application/json'
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            
            this.getProcesses();
        },
        methods: {
            getProcesses() {
                let uri = '/api/processes';
                this.axios.get(uri)
            	    .then((response) => {
            	        if(response.data.data) {
                	        this.processes = response.data.data;
            	        }
            	        else if (response.data.message) {
                            this.message = response.data.message;
                            swal("Ошибка", this.message, "error");
                            this.$router.push({name: 'dashboard'});
                        }
                        else {
                            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к списку процессов", "error");
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
            deleteProcess(id) {
                let uri = `/api/processes/${id}`;
                if (confirm("Do you really want to delete it?")) {
                    this.axios.delete(uri)
                        .then((response) => {
                            if(response.data.data) {
                                this.processes.splice(this.processes.indexOf(id), 1);
                            }
                            else if (response.data.message) {
                                this.message = response.data.message;
                                swal("Ошибка", this.message, "error");
                            }
                            else {
                                swal("Ошибка", "Нет ответа от сервера при попытке удаления выбранного процесса", "error");
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