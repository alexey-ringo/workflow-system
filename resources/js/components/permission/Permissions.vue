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
                        <th>SLUG</th>
                        <th>Политика безопасности</th>
                        <th>Редактировать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="permission in permissions" :key="permission.id">
                        <td>{{ permission.name  }}</td>
                        <td>{{ permission.slug  }}</td>
                        <td>Политики</td>
                        <td>
                            <router-link :to="{name: 'permission-update', params: {id: permission.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <button class="btn btn-danger" @click.prevent = "deletePermission(permission.id)">Удалить</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Разрешение операций</th>
                        <th>SLUG</th>
                        <th>Политика безопасности</th>
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
                permissions: []
            }
        },
        mounted() {
            let uri = '/api/permissions';
            this.axios.get(uri)
            	.then((response) => {
                	this.permissions = response.data.data;
                })
                .catch(e => {
                	swal('Ошибка', "Внутренняя ошибка сервера", "error");
                });
        },
        methods: {
            deletePermission(id) {
                let uri = `/api/permissions/${id}`;
                if (confirm("Do you really want to delete it?")) {
                    this.axios.delete(uri)
                        .then((response) => {
                            if(response.data) {
                                this.permissions.splice(this.permissions.indexOf(id), 1);
                            }
                            else {
                                swal("Удаление политики", "Что то пошло не так...", "error");
                            }
                        })
                        .catch(e => {
                            swal('Ошибка', "Внутренняя ошибка сервера", "error");
                        });
                }
            }
        }
    }
    
</script>