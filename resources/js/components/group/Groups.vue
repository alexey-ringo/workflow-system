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
                        <th>SLUG</th>
                        <th>Выполняемые задачи</th>
                        <th>Редактировать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="group in groups" :key="group.id">
                        <td>{{ group.name  }}</td>
                        <td>{{ group.slug  }}</td>
                        <td>{{ relatedMissions(group.missions) }}</td>
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
                        <th>SLUG</th>
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
                groups: []
            }
        },
        mounted() {
            let token = localStorage.getItem('jwt')

            this.axios.defaults.headers.common['Content-Type'] = 'application/json'
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            
            let uri = '/api/groups';
            this.axios.get(uri)
            	.then((response) => {
                	this.groups = response.data.data;
                })
                .catch(e => {
                	//console.log(e);
                	swal('Ошибка', "Внутренняя ошибка сервера", "error");
                });
        },
        methods: {
            deleteGroup(id) {
                let uri = `/api/groups/${id}`;
                if (confirm("Do you really want to delete it?")) {
                    this.axios.delete(uri)
                        .then((response) => {
                            if(response.data) {
                                this.groups.splice(this.groups.indexOf(id), 1);
                            }
                            else {
                                swal("Удаление рабочей группы", "Что то пошло не так...", "error");
                            }
                        })
                        .catch(e => {
                            swal('Ошибка', "Внутренняя ошибка сервера", "error");
                        });
                }
            },
            relatedMissions(relMission) {
                let missions = '';
    			for(let i = 0; i < relMission.length; i++) {
    				missions += relMission[i].name + ', ';
    			}
				return missions;
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