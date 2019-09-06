<template>
  <!-- Horizontal Form -->
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Задача для нового контракта</h3>
    </div>
    <!-- /.card-header -->
    
    
    
    
    
    
    <!-- form start -->
    <form v-if="!isEmptyObject(customerByPhone)" class="form-horizontal" @submit.prevent="createTask(selectRoute)">
      <div class="card-body">
        <div class="form-group">
          <label class="col-sm-4 control-label">Обращение по существующему клиенту: <b>{{ customerByPhone.surname }}</b></label>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Тематика обращения по существующему клиенту</label>
          <div class="col-sm-10">
            <select v-model="selectRoute" required>
              <option v-for="routeItem in routesForCurrentCustomers" :value="routeItem.value" :key="routeItem.id">
                {{ routeItem.name }}
              </option>
            </select>
          </div>
        </div>
        
        <div class="col-sm-4 table-responsive p-0">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Контракты</th>
                        <th>Обращение</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="contractItem in customerByPhone.contracts" :key="contractItem.id">
                        <td>{{ contractItem.contract_num  }}</td>
                        <td>
                            
                                Edit
                            
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Телефон</th>
                        <th>Редактировать</th>
                    </tr>
                </tfoot>
            </table>
        </div>
                  
        <div class="form-group">
          <label for="inputTaskDesc" class="col-sm-4 control-label">Краткое описание</label>
          <div class="col-sm-10">
            <input type="text" v-model="task.description" class="form-control" placeholder="Краткое описание">
          </div>
        </div>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button class="btn btn-primary">Создать</button>
        <button class="btn btn-default float-right" @click="setDefault">Отмена</button>
      </div>
      <!-- /.card-footer -->
    </form>
    
    
    <div v-if="defaultInputs" class="card-footer">
      <router-link :to="{name: 'tasks'}" class="btn btn-default float-right">Отмена</router-link>
    </div>
    <!-- /.card-footer -->
    
  </div>
  <!-- /.card -->
</template>

<script>
  export default {
    data(){
      return {
        task: {},
        routesForCurrentCustomers: [],
        selectRoute: null,
        keywordsPhone: null,
        keywordsSurname: null,
        customerByPhone: {},
        resultsSurname: [],
        phoneNotFound: false,
        defaultInputs: true
      }
    },
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
      
      let uri = '/api/routes';
      this.axios.get(uri)
      	.then((response) => {
        	this.routesForCurrentCustomers = response.data.data;
        })
        .catch(e => {
        	if(e == 'Error: Request failed with status code 401') {
            if (localStorage.getItem('jwt')) {
              localStorage.removeItem('jwt');
              this.$router.push({name: 'login'});
            }
            //swal('Ошибка аутентификации', "Ползователь не зарегистрирован", "error");
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            this.$router.push({name: 'tasks'});
          }
        });
    },
    watch: {
      keywordsPhone(after, before) {
        if(this.keywordsPhone.length === 11) {
          this.fetchPhone();
        }
      },
      keywordsSurname(after, before) {
        this.fetchSurname();
      }
    },
    methods: {
      createTask(route) {
        let uri = '/api/tasks';
        //if(this.phoneNotFound == true) {
        //  this.task.route = 2;
        //}
        this.task.route = route;
        
        this.axios.post(uri, this.task).then((response) => {
          if(response.data.data) {
            //swal("Заказ", "Ваш заказ принят!", "success");
            this.$router.push({name: 'tasks'});
          }
          else {
            swal("Сохранение изменений", "Что то пошло не так...", "error");
            this.$router.push({name: 'tasks'});
          }
        })
        .catch(e => {
          if(e == 'Error: Request failed with status code 401') {
            if (localStorage.getItem('jwt')) {
              localStorage.removeItem('jwt');
              this.$router.push({name: 'login'});
            }
            //swal('Ошибка аутентификации', "Ползователь не зарегистрирован", "error");
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            this.$router.push({name: 'tasks'});
          }
        });
      },
      fetchPhone() {
        //this.axios.get('/api/search-phone', { params: { keywords: this.keywords } })
        //  .then(response => this.results = reponse.data)
        //  .catch(error => {});
        let uri = '/api/search-phone';
          this.axios.get(uri, { params: { keywords: this.keywordsPhone }})
          	.then((response) => {
              if(response.data.data) {
                this.customerByPhone = response.data.data;
                this.phoneNotFound = false;
                this.defaultInputs = false;
              }
              else {
                this.customerByPhone = {};
                this.phoneNotFound = true;
                this.defaultInputs = false;
              }
            })
            .catch(e => {
            	//console.log(e);
            	swal('Ошибка', "Внутренняя ошибка сервера", "error");
            });
        
      },
      fetchSurname() {
        //this.axios.get('/api/search-phone', { params: { keywords: this.keywords } })
        //  .then(response => this.results = reponse.data)
        //  .catch(error => {});
        let uri = '/api/search-surname';
          this.axios.get(uri, { params: { keywords: this.keywordsSurname }})
          	.then((response) => {
              	this.resultsSurname = response.data.data;
              })
              .catch(e => {
              	//console.log(e);
              	swal('Ошибка', "Внутренняя ошибка сервера", "error");
              });
        
      },
      isEmptyObject(obj) {
        for (var i in obj) {
          if (obj.hasOwnProperty(i)) {
            return false;
          }
        }
      return true;
      },
      setDefault() {
        this.task = {};
        this.keywordsPhone = null;
        this.keywordsSurname = null;
        this.customerByPhone = {};
        this.resultsSurname = [];
        this.phoneNotFound = false;
        this.defaultInputs = true;
      },
      isRouteForCurrentCustomer(routeVal) {
        if(routeVal == 1) {
          return false;
        }
        else {
          return true;
        }
      },
    },
    computed: {
      isEmptyCustomerByPhone() {
        for (var i in this.customerByPhone) {
          if (this.customerByPhone.hasOwnProperty(i)) {
            return false;
          }
        }
      return true;
      }
    },
  }
</script>