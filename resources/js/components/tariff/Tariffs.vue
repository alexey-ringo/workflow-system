<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Тарифы</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Название тарифа</th>
                        <th>Описание</th>
                        <th>SKU</th>
                        <th>Прайс</th>
                        <th>Статус</th>                        
                        <th>Редактировать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="tariff in tariffs" :key="tariff.id">
                        <td>{{ tariff.title }}</td>
                        <td>{{ tariff.description }}</td>
                        <td>{{ tariff.sku }}</td>
                        <td>{{ tariff.price }}</td>
                        <td>{{ tariff.is_active }}</td>
                        <td>
                            <router-link :to="{name: 'tariff-update', params: {id: tariff.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Название тарифа</th>
                        <th>Описание</th>
                        <th>SKU</th>
                        <th>Прайс</th>
                        <th>Статус</th>                        
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
                tariffs: [],
                message: ''
            }
        },
        mounted() {
            let token = localStorage.getItem('jwt');            

            this.axios.defaults.headers.common['Content-Type'] = 'application/json';
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            
            this.getTariffs();
        },
        methods: {
            getTariffs() {
                let uri = '/api/tariffs';
                this.axios.get(uri)
            	    .then((response) => {                        
            	        if(response.data.data) {
                	        this.tariffs = response.data.data;
            	        }
            	        else if (response.data.message) {
                            this.message = response.data.message;
                            swal("Ошибка", this.message, "error");
                            this.$router.push({name: 'dashboard'});
                        }
                        else {
                            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к списку тарифов", "error");
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
        },
        beforeRouteEnter (to, from, next) { 
            if ( ! localStorage.getItem('jwt')) {
                return next('login')
            }

            next()
        }
    }
    
</script>