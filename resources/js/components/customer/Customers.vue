<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Клиенты</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Фамилия</th>
                        <th>Имя</th>
                        <th>Отчество</th>
                        <th>Город</th>
                        <th>Улица</th>
                        <th>Дом</th>
                        <th>Квартира</th>
                        <th>Email</th>
                        <th>Редактировать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="customer in customers" :key="customer.id">
                        <td>{{ customer.surname  }}</td>
                        <td>{{ customer.name  }}</td>
                        <td>{{ customer.second_name  }}</td>
                        <td>{{ customer.city  }}</td>
                        <td>{{ customer.street  }}</td>
                        <td>{{ customer.building  }}</td>
                        <td>{{ customer.flat  }}</td>
                        <td>{{ customer.email  }}</td>
                        <td>
                            <router-link :to="{name: 'customer-update', params: {id: customer.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <!--
                            <button class="btn btn-danger" @click.prevent = "deleteCustomer(customer.id)">Удалить</button>
                            -->
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Фамилия</th>
                        <th>Имя</th>
                        <th>Отчество</th>
                        <th>Город</th>
                        <th>Улица</th>
                        <th>Дом</th>
                        <th>Квартира</th>
                        <th>Email</th>
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
                customers: []
            }
        },
        mounted() {
            let token = localStorage.getItem('jwt')

            this.axios.defaults.headers.common['Content-Type'] = 'application/json'
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            
            let uri = '/api/customers';
            this.axios.get(uri)
            	.then((response) => {
                	this.customers = response.data.data;
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
            deleteCustomer(id) {
                let uri = `/api/customers/${id}`;
                if (confirm("Do you really want to delete it?")) {
                    this.axios.delete(uri)
                        .then((response) => {
                            if(response.data.data) {
                                this.customers.splice(this.customers.indexOf(id), 1);
                            }
                            else {
                                swal("Удаление задачи", "Что то пошло не так...", "error");
                            }
                        })
                        .catch(e => {
                            swal('Ошибка', "Внутренняя ошибка сервера", "error");
                        });
                }
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