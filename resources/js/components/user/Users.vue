<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Заказы</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Имя пользователя</th>
                        <th>email</th>
                        <th>Выполняемые задачи</th>
                        <th>Разрешенные операции</th>
                        <th>Редактировать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.name  }}</td>
                        <td>{{ user.email  }}</td>
                        <td>Win 95+</td>
                        <td> 4</td>
                        <td>
                            <router-link :to="{name: 'user-update', params: {id: user.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <button class="btn btn-danger" @click.prevent = "deleteUser(user.id)">Удалить</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Имя пользователя</th>
                        <th>email</th>
                        <th>Выполняемые задачи</th>
                        <th>Разрешенные операции</th>
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
                users: []
            }
        },
        mounted() {
            let uri = '/api/users';
            this.axios.get(uri)
            	.then((response) => {
                	this.users = response.data.data;
                })
                .catch(e => {
                	//console.log(e);
                });
        },
        methods: {
            deleteUser(id) {
                let uri = '/api/users/${id}';
                if (confirm("Do you really want to delete it?")) {
                    this.axios.delete(uri)
                        .then((response) => {
                            if(response.data) {
                                this.users.splice(this.users.indexOf(id), 1);
                            }
                            else {
                                console.log('could mot delete');
                            }
                        })
                        .catch(e => {
                            alert("Could not delete this User");
                        });
                }
            }
        }
    }
    
</script>