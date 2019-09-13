<template>
  <!-- Horizontal Form -->
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Задача для сущ. контракта № {{ contract.contract_num }} у клиента {{ contract.customer.name }} {{ contract.customer.surname }}</h3>
    </div>
    <!-- /.card-header -->
    
    
    
    
    
    
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="createTask(selectRoute)">
      <div class="card-body">
        <div class="form-group">
          <label class="col-sm-4 control-label">Обращение по существующему клиенту: <b></b></label>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Тематика обращения по существующему клиенту</label>
          <div class="col-sm-10">
            <select v-model="selectRoute" required>
              <option v-for="routeItem in routes" :value="routeItem.value" :key="routeItem.id">
                {{ routeItem.name }}
              </option>
            </select>
          </div>
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
        <router-link :to="{name: 'contracts-for-customer', params: {customid: contract.customer.id}}" class="btn btn-default float-right">Отмена</router-link>
      </div>
      <!-- /.card-footer -->
    </form>
    
    <!-- /.card-footer -->
    
  </div>
  <!-- /.card -->
</template>

<script>
  export default {
    data(){
      return {
        task: {},
        contract: {},
        taskResponse: {},
        routes: [],
        selectRoute: null,
      }
    },
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
      this.getRoutes();
      this.getContract();
    },
    methods: {
      getRoutes() {
        let uri = '/api/routes?filter=1';
        this.axios.get(uri)
      	  .then((response) => {
      	    if(!response.data.data) {
              swal("Ошибка", "Нет ответа от сервера при чтении маршрутов", "error");
              this.$router.push({name: 'search-customer'});
            }
        	  this.routes = response.data.data;
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
              this.$router.push({name: 'search-customer'});
            }
          });
      },
      getContract() {
        let uri = `/api/contracts/${this.$route.params.contractid}`;
        this.axios.get(uri).then((response) => {
          if(!response.data.data) {
            swal("Ошибка", "Нет ответа от сервера при доступе к контракту клиента", "error");
            this.$router.push({name: 'search-customer'});
          }
          this.contract = response.data.data;
          if(this.isEmptyObject(this.contract)) {
            swal('Ошибка', 'Контракт № "' + this.$route.params.contractid + '" не найден!', 'error');
            this.$router.push({name: 'search-customer'});
          }
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
            this.$router.push({name: 'search-customer'});
          }
        });
      },
      createTask(route) {
        let uri = '/api/tasks';
        this.task.route = route;
        this.task.contract_id = this.contract.id;
        this.axios.post(uri, this.task).then((response) => {
          if(!response.data.data) {
            swal("Ошибка", "Нет ответа от сервера при закрытии задачи", "error");
            this.$router.push({name: 'contracts-for-customer'});
          }
          this.taskResponse = response.data.data;
          if(!this.taskResponse.hasOwnProperty('error')) {
            swal("Ошибка", "Неверный формат ответа сервера при создании задачи", "error");
          	this.$router.push({name: 'contracts-for-customer', params: {customid: this.contract.customer.id}});
          }
          if(!this.taskResponse.error) {
            //this.$emit("changecartevent", 1);
            swal("Сохранение изменений", this.taskResponse.message, "success");
            this.$router.push({name: 'contracts-for-customer', params: {customid: this.contract.customer.id}});
          }
          else {
          	swal("Ошибка", this.taskResponse.message, "error");
          	this.$router.push({name: 'contracts-for-customer', params: {customid: this.contract.customer.id}});
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
            this.$router.push({name: 'contracts-for-customer', params: {customid: this.contract.customer.id}});
          }
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
    },
  }
</script>