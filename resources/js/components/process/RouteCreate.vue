<template>
  <!-- Horizontal Form -->
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Новый маршрут для обработки клиентских заявок</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="addRoute">
      <div class="card-body">
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Название маршрута</label>
          <div class="col-sm-10">
            <input type="text" v-model="route.title" class="form-control" required placeholder="Название маршрута">
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Идентификатор маршрута</label>
          <div class="col-sm-10">
            <input type="text" v-model="route.value" class="form-control" required placeholder="id-маршрута">
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Описание маршрута</label>
          <div class="col-sm-10">
            <input type="text" v-model="route.description" class="form-control" placeholder="Описание маршрута">
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="form-group">
            <input type="checkbox" v-model="routeStatus">
            <label for="superCheckbox">Маршрут используется</label>
          </div>
        </div>
      </div>
      <!-- /.card-body -->
  
      <div class="card-footer">
        <button class="btn btn-primary">Создать</button>
        <router-link :to="{name: 'routes'}" class="btn btn-default float-right">Отмена</router-link>
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
        route: {},
        message: '',
        routeStatus: false
      }
    },
    /*
    create() {
    },
    */
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json';
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    },
    methods: {
      addRoute(){
        if(this.routeStatus) {
          this.route.is_active = 1;
        }
        else {
          this.route.is_active = null;
        }
        
        let uri = '/api/routes';
        this.axios.post(uri, this.route).then((response) => {
          if(response.data.message) {
            this.message = response.data.message;                            
            swal("Сохранение изменений", this.message, "success");
            this.$router.push({name: 'routes'});  
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при создании маршрута для обработки клиентских заявок", "error");
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
                this.$router.push({name: 'routes'});
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
            console.log('Внутренняя ошибка: ' + error.message);
            this.$router.push({name: 'routes'});
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