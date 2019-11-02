<template>
  <!-- Horizontal Form -->
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Новый процесс обработки клиентских заявок</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="addProcess">
      <div class="card-body">
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Маршрут</label>
          <div class="col-sm-10">
            <select v-model="selectRoute" required>
              <option v-for="route in routes" :value="route.id" :key="route.id">
                {{ route.title }}
              </option>
            </select>
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Название процесса</label>
          <div class="col-sm-10">
            <input type="text" v-model="process.title" class="form-control" required placeholder="Название процесса">
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Норматив времени выполнения, час.</label>
          <div class="col-sm-10">
            <select v-model="selectDeadline" required>
              <option v-for="(deadline, index) in deadlines" :value="deadline" :key="index">
                {{ index }}
              </option>
            </select>
          </div>
        </div>
                
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Очередь выполнения процесса</label>
              <input type="text" v-model="process.sequence" class="form-control" required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="checkbox" v-model="superChecked">
              <label>Процесс супервайзера</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="checkbox" v-model="finalChecked">
              <label>Финальный процесс</label>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <input type="checkbox" v-model="processStatus">
              <label for="superCheckbox">Процесс используется</label>
            </div>
          </div>

        </div>
        
      </div>
      <!-- /.card-body -->
  
      <div class="card-footer">
        <button class="btn btn-primary">Создать</button>
        <router-link :to="{name: 'processes'}" class="btn btn-default float-right">Отмена</router-link>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>
  <!-- /.card -->
</template>

<script>
  export default {
    data(){
      return {
        process: {},
        routes: [],
        deadlines: [],
        message: '',
        processStatus: false,
        superChecked: false,
        finalChecked: false,
        selectRoute: false,
        selectDeadline: false,
      }
    },
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json';
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
      
      this.getRoutes();
      this.getDeadlines();
    },
    methods: {
      getRoutes() {
        let uri = '/api/routes';
        this.axios.get(uri).then((response) => {
          if(response.data.data) {
            this.routes = response.data.data;
          }
          else if (response.data.message) {
            this.message = response.data.message;
            swal("Ошибка", this.message, "error");
            this.$router.push({name: 'processes'});
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к списку маршрутов", "error");
            this.$router.push({name: 'processes'});
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
                this.$router.push({name: 'processes'});
              }
            }         
          }
          else if(error.request) {
            //console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            console.log('Внутренняя ошибка: ' + error.message);
            this.$router.push({name: 'processes'});
          }
        });
      },
      getDeadlines() {
        let uri = '/api/frontrequest-deadlines';
        this.axios.get(uri).then((response) => {
          if(response.data.data) {
            this.deadlines = response.data.data;
          }
          else if (response.data.message) {
            this.message = response.data.message;
            swal("Ошибка", this.message, "error");
            this.$router.push({name: 'processes'});
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к списку дедлайнов", "error");
            this.$router.push({name: 'processes'});
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
                this.$router.push({name: 'processes'});
              }
            }         
          }
          else if(error.request) {
            //console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            console.log('Внутренняя ошибка: ' + error.message);
            this.$router.push({name: 'processes'});
          }
        });
      },
      addProcess(){
        if(this.processStatus) {
          this.process.is_active = 1;
        }
        else {
          this.process.is_active = null;
        }

        if(this.superChecked) {
          this.process.is_super = 1;
        }
        else {
          this.process.is_super = null;
        }
        
        if(this.finalChecked) {
          this.process.is_final = 1;
        }
        else {
          this.process.is_final = null;
        }
        
        this.process.route_id = this.selectRoute;
        this.process.deadline = this.selectDeadline;
        
        let uri = '/api/processes';
        this.axios.post(uri, this.process).then((response) => {
          if(response.data.message) {
            this.message = response.data.message;                            
            swal("Сохранение изменений", this.message, "success");
            this.$router.push({name: 'processes'});  
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при создании нового процесса", "error");
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
                this.$router.push({name: 'processes'});
              }
            }//Ошибки валидации
            else {
              swal('Ошибка - ' + error.response.status, this.errMessageToStr(error.response.data), "error");
            }
          }
          else if(error.request) {
            //console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            //console.log('Внутренняя ошибка: ' + error.message);
            this.$router.push({name: 'processes'});
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
    }
  }
</script>