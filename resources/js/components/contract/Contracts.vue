<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Контракты</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Контракт</th>
                        <th>Клиент</th>
                        <th>Прайс</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="contract in contracts" :key="contract.id">
                        <td>{{ contract.contract_num  }}</td>
                        <td>{{ contract.customer.name + ' ' +  contract.customer.surname }}</td>
                        <td>{{ contract.price  }}</td>
                        <td>
                            <router-link :to="{name: 'contract-update', params: {id: contract.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <!--
                            <button class="btn btn-danger" @click.prevent = "deleteContract(contract.id)">Удалить</button>
                            -->
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Контракт</th>
                        <th>Клиент</th>
                        <th>Прайс</th>
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
                contracts: []
            }
        },
        mounted() {
            let token = localStorage.getItem('jwt')

            this.axios.defaults.headers.common['Content-Type'] = 'application/json'
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            
            let uri = '/api/contracts';
            this.axios.get(uri)
            	.then((response) => {
                	this.contracts = response.data.data;
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
            deleteContract(id) {
                let uri = `/api/contracts/${id}`;
                if (confirm("Do you really want to delete it?")) {
                    this.axios.delete(uri)
                        .then((response) => {
                            if(response.data) {
                                this.contracts.splice(this.contracts.indexOf(id), 1);
                            }
                            else {
                                swal("Удаление контракта", "Что то пошло не так...", "error");
                            }
                        })
                        .catch(e => {
                            swal('Ошибка', "Внутренняя ошибка сервера", "error");
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