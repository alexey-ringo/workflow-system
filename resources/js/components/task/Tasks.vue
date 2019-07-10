<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="card-title">Рабочие группы участников БП</h3>
                </div>
                <div class="col-md-4">
                    <router-link :to="{name: 'task-create'}" class="btn btn-xs btn-default" v-if="visibleCreate">
                                Новая заявка
                            </router-link>
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Номер заявки</th>
                        <th>Процесс</th>
                        <th>Название заявки</th>
                        <th>Краткое описание</th>
                        <th>Обработать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="task in tasks" :key="task.id">
                        <td>{{ task.task  }}</td>
                        <td>{{ task.sequence  }}</td>
                        <td>{{ task.title  }}</td>
                        <td>{{ task.description }}</td>
                        <td>
                            <router-link :to="{name: 'task-update', params: {id: task.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                        </td>
                    </tr>
                </tbody>
                <tbody>
                    <tr v-for="task in tasks" :key="task.id">
                        <td>{{ task.task  }}</td>
                        <td>{{ task.sequence  }}</td>
                        <td>{{ task.title  }}</td>
                        <td>{{ task.description }}</td>
                        <td>
                            <router-link :to="{name: 'task-update', params: {id: task.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Номер заявки</th>
                        <th>Процесс</th>
                        <th>Название заявки</th>
                        <th>Краткое описание</th>
                        <th>Обработать</th>
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
                tasks: [],
                meta: []
            }
        },
        mounted() {
            let token = localStorage.getItem('jwt')

            this.axios.defaults.headers.common['Content-Type'] = 'application/json'
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            
            let uri = '/api/tasks';
            this.axios.get(uri)
            	.then((response) => {
                	this.tasks = response.data.data;
                	this.meta = response.data.meta;
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
            
        },
        computed: {
            visibleCreate() {
                return this.meta['canTaskCreate'];
            },
            maxSequenceNum() {
                //let arr = [];
				//let j = 0;
    			//for(let i = 0; i < this.tasks.length; i++) {
    			//	if(this.globalProducts.products[i].color.value == this.selectedColor) {
    			//		arr[j] = this.globalProducts.products[i].size.value;
    			//		j++;
    			//	}
    			//}
				//let result = Array.from(new Set(arr));
				//return result;
				//return arr;
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