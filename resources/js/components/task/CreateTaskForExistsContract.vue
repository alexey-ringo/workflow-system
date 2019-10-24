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
          <label class="col-sm-4 control-label">Первоначальный комментарий к сервисной задаче</label>
          <div class="col-sm-10">
            <textarea class="form-control" v-model="task.comment" placeholder="Первоначальный комментарий к сервисной задаче" style="height: 300px">
            </textarea>
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
        message: '',
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
      	    if(response.data.data) {
      	      this.routes = response.data.data;
      	    }
      	    else {
      	      swal("Ошибка", "Нет ответа от сервера при чтении маршрутов", "error");
              this.$router.push({name: 'search-customer'});
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
                  this.$router.push({name: 'search-customer'});
                }
              }         
            }
            else if(error.request) {
              console.log(error.request.data);
            }
            else {
              swal('Ошибка', "Внутренняя ошибка сервера", "error");
              console.log('Внутренняя ошибка: ' + error.message);
              this.$router.push({name: 'search-customer'});
            }
          });
      },
      getContract() {
        let uri = `/api/contracts/${this.$route.params.contractid}`;
        this.axios.get(uri).then((response) => {
          if(response.data.data) {
            this.contract = response.data.data;
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при доступе к контракту клиента", "error");
            this.$router.push({name: 'search-customer'});
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
                this.$router.push({name: 'search-customer'});
              }
            }         
          }
          else if(error.request) {
            console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            console.log('Внутренняя ошибка: ' + error.message);
            this.$router.push({name: 'search-customer'});
          }
        });
      },
      createTask(route) {
        let uri = '/api/tasks';
        this.task.route = route;
        this.task.contract_id = this.contract.id;
        this.axios.post(uri, this.task).then((response) => {
          if(response.data.message) {
            this.message = response.data.message;
            swal("Сохранение изменений", this.message, "success");
            this.$router.push({name: 'contracts-for-customer', params: {customid: this.contract.customer.id}});
          }
          else {
            swal("Ошибка", "Нет ответа сервера при создании задачи", "error");
          	this.$router.push({name: 'contracts-for-customer', params: {customid: this.contract.customer.id}});
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
                this.$router.push({name: 'contracts-for-customer', params: {customid: this.contract.customer.id}});
              }
            }//Ошибки валидации
            else {
              swal('Ошибка - ' + error.response.status, this.errMessageToStr(error.response.data), "error");
            }
          }
          else if(error.request) {
            console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            console.log('Внутренняя ошибка: ' + error.message);
            this.$router.push({name: 'contracts-for-customer', params: {customid: this.contract.customer.id}});
          }
        });
      },
      errMessageToStr(errors) {
          let result = '';
          for(let key in errors) {
            errors[key].forEach(function(item){
              result += item + '; ';
            });
          }
          return result;
      },
    },
  }
</script>