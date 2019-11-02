<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Рабочие группы участников БП</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Рабочая группа</th>
                        <th>Выполняемые задачи</th>
                        <th>Редактировать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="group in groups" :key="group.id">
                        <td>{{ group.title  }}</td>
                        <td>{{ relatedProcesses(group.processes) }}</td>
                        <td>
                            <router-link :to="{name: 'group-update', params: {id: group.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <button class="btn btn-danger" @click.prevent = "deleteGroup(group.id)">Удалить</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Рабочая группа</th>
                        <th>Выполняемые задачи</th>
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
                groups: [],
                message: ''
            }
        },
        mounted() {
            let token = localStorage.getItem('jwt')

            this.axios.defaults.headers.common['Content-Type'] = 'application/json'
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            
            this.getGroups();
        },
        methods: {
            getGroups() {
                let uri = '/api/groups';
                this.axios.get(uri)
            	    .then((response) => {
            	        if(response.data.data) {
                	        this.groups = response.data.data;
            	        }
            	        else if (response.data.message) {
                            this.message = response.data.message;
                            swal("Ошибка", this.message, "error");
                            this.$router.push({name: 'dashboard'});
                        }
                        else {
                            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к списку рабочих групп", "error");
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
            deleteGroup(id) {
                let uri = `/api/groups/${id}`;
                if (confirm("Do you really want to delete it?")) {
                    this.axios.delete(uri)
                        .then((response) => {
                            if(response.data.data) {
                                this.groups.splice(this.groups.indexOf(id), 1);
                            }
                            else if (response.data.message) {
                                this.message = response.data.message;
                                swal("Ошибка", this.message, "error");
                            }
                            else {
                                swal("Ошибка", "Нет ответа от сервера при попытке удаления выбранной группы", "error");
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
            },
            relatedProcesses(relProcess) {
                let processes = '';
    			for(let i = 0; i < relProcess.length; i++) {
    				processes += relProcess[i].title + ', ';
    			}
				return processes;
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