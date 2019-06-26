<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Политики безопасности для пользователей</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Политика безопасности</th>
                        <th>SLUG</th>
                        <th>Разрешенные операции</th>
                        <th>Редактировать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="role in roles" :key="role.id">
                        <td>{{ role.name  }}</td>
                        <td>{{ role.slug  }}</td>
                        <td>{{ relatedPermissions(role.permissions) }}</td>
                        <td>
                            <router-link :to="{name: 'role-update', params: {id: role.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <button class="btn btn-danger" @click.prevent = "deleteRole(role.id)">Удалить</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Роль</th>
                        <th>SLUG</th>
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
                roles: []
            }
        },
        mounted() {
            let uri = '/api/roles';
            this.axios.get(uri)
            	.then((response) => {
                	this.roles = response.data.data;
                })
                .catch(e => {
                	//console.log(e);
                	swal('Ошибка', "Внутренняя ошибка сервера", "error");
                });
        },
        methods: {
            deleteRole(id) {
                let uri = `/api/roles/${id}`;
                if (confirm("Do you really want to delete it?")) {
                    this.axios.delete(uri)
                        .then((response) => {
                            if(response.data) {
                                this.roles.splice(this.roles.indexOf(id), 1);
                            }
                            else {
                                swal("Удаление политики", "Что то пошло не так...", "error");
                            }
                        })
                        .catch(e => {
                            swal('Ошибка', "Внутренняя ошибка сервера", "error");
                        });
                }
            },
            relatedPermissions(relPerm) {
                let permissions = '';
    			for(let i = 0; i < relPerm.length; i++) {
    				permissions += relPerm[i].name + ', ';
    			}
				return permissions;
            }
        },
    }
</script>