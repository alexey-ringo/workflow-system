<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="card-title">Обращения клиентов</h3>
                </div>
                <div class="col-md-4">
                    <router-link :to="{name: 'search-customer'}" class="btn btn-xs btn-default" v-if="visibleCreate">
                                Новое обращение
                            </router-link>
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered table-striped" v-for="process in processes" :key="process.id">
                <caption class="table-caption-top">Процесс: {{ process.name  }}</caption>
                <thead>
                    <tr>
                        <th>Номер заявки</th>
                        <th>Процесс</th>
                        <th>Шаг</th>
                        <th>Тематика</th>
                        <th>Краткое описание</th>
                        <th>Получена от</th>
                        <th>Время поступления</th>
                        <th>Обработать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="task in process.tasks" :key="process.id">
                        <td v-if="task.status">{{ task.task  }}</td>
                        <td v-if="task.status">{{ task.process_sequence  }}</td>
                        <td v-if="task.status">{{ task.task_sequence  }}</td>
                        <td v-if="task.status">{{ task.title  }}</td>
                        <td v-if="task.status">{{ task.contract.customer.surname + ' ' + task.contract.customer.name + ' ' + task.contract.customer.second_name }}</td>
                        <td v-if="task.status">{{ task.creating_user_name }}</td>
                        <td v-if="task.status">{{ task.created_at }}</td>
                        <td v-if="task.status">
                            <router-link :to="{name: 'task-update', params: {id: task.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
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
                message: '',
                //tasks: [],
                meta: []
            }
        },
        mounted() {
            let token = localStorage.getItem('jwt')

            this.axios.defaults.headers.common['Content-Type'] = 'application/json'
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            
            this.getTasks();
        },
        methods: {
            getTasks() {
                let uri = '/api/tasks';
                this.axios.get(uri)
            	    .then((response) => {
            	        if(response.data.data) {
            	            this.processes = response.data.data;
                	        //this.tasks = response.data.data;
                	        this.meta = response.data.meta;
            	        }
            	        else if (response.data.message) {
                            this.message = response.data.message;
                            swal("Ошибка", this.message, "error");
                            this.$router.push({name: 'dashboard'});
                        }
                        else {
                            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к списку задач", "error");
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
            }
        },
        computed: {
            visibleCreate() {
                return this.meta['canTaskCreate'];
            },
        },
        beforeRouteEnter (to, from, next) { 
            if ( ! localStorage.getItem('jwt')) {
                return next('login')
            }
            next()
        }
    }
    
</script>