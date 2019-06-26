<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Процессы для рабочих групп</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Процесс</th>
                        <th>Очередь выполнения процесса</th>
                        <th>Процесс супервайзера</th>
                        <th>Завершение очереди</th>
                        <th>Редактировать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="mission in missions" :key="mission.id">
                        <td>{{ mission.name  }}</td>
                        <td>{{ mission.queue  }}</td>
                        <td>{{ mission.is_super  }}</td>
                        <td>{{ mission.is_final  }}</td>
                        <td>
                            <router-link :to="{name: 'mission-update', params: {id: mission.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <button class="btn btn-danger" @click.prevent = "deleteMission(mission.id)">Удалить</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Процесс</th>
                        <th>Очередь выполнения процесса</th>
                        <th>Процесс супервайзера</th>
                        <th>Завершение очереди</th>
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
                missions: []
            }
        },
        mounted() {
            let uri = '/api/missions';
            this.axios.get(uri)
            	.then((response) => {
                	this.missions = response.data.data;
                })
                .catch(e => {
                	swal('Ошибка', "Внутренняя ошибка сервера", "error");
                });
        },
        methods: {
            deleteMission(id) {
                let uri = `/api/missions/${id}`;
                if (confirm("Do you really want to delete it?")) {
                    this.axios.delete(uri)
                        .then((response) => {
                            if(response.data) {
                                this.missions.splice(this.missions.indexOf(id), 1);
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
        }
    }
    
</script>