<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Все разрешения операций</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Разрешение операций</th>                        
                        <th>Политика безопасности</th>
                        <th>Удалить</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="permission in permissions" :key="permission.id">
                        <td>{{ permission.title  }}</td>                        
                        <td>Политики</td>
                        <td>
                            
                            <button class="btn btn-danger" @click.prevent = "deletePermission(permission.id)">Удалить</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Разрешение операций</th>                        
                        <th>Политика безопасности</th>
                        <th>Удалить</th>
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
                permissions: [],
                message: ''
            }
        },
        mounted() {
            let token = localStorage.getItem('jwt')

            this.axios.defaults.headers.common['Content-Type'] = 'application/json'
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            
            this.getPermissions();
        },
        methods: {
            getPermissions() {
                let uri = '/api/permissions';
                this.axios.get(uri)
            	    .then((response) => {
            	        if(response.data.data) {
                	        this.permissions = response.data.data;
            	        }
            	        else if (response.data.message) {
                            this.message = response.data.message;
                            swal("Ошибка", this.message, "error");
                            this.$router.push({name: 'dashboard'});
                        }
                        else {
                            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к списку всех разрешений операций", "error");
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
            deletePermission(id) {
                let uri = `/api/permissions/${id}`;
                if (confirm("Do you really want to delete it?")) {
                    this.axios.delete(uri)
                        .then((response) => {
                            if(response.data.data) {
                                this.permissions.splice(this.permissions.indexOf(id), 1);
                            }
                            else if (response.data.message) {
                                this.message = response.data.message;
                                swal("Ошибка", this.message, "error");
                            }
                            else {
                                swal("Ошибка", "Нет ответа от сервера при попытке удаления выбранного разрешения", "error");
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