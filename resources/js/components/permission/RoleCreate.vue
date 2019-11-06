<template>
  <!-- Horizontal Form -->
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Новая политика безопасности</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="addRole">
      <div class="card-body">
        <div class="form-group">
          <label for="inputRoleTitle" class="col-sm-4 control-label">Название политики безопасности</label>
          <div class="col-sm-10">
            <input type="text" v-model="role.title" class="form-control" id="inputRoleName" required placeholder="Название политики безопасности">
          </div>
        </div>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button class="btn btn-primary">Создать</button>
          <router-link :to="{name: 'roles'}" class="btn btn-default float-right">Отмена</router-link>
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
        role:{},
        message: ''
      }
    },
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json';
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    },
    methods: {
      addRole(){
        let uri = '/api/roles';
        this.axios.post(uri, this.role).then((response) => {
          if(response.data.message) {
            this.message = response.data.message;                            
            swal("Сохранение изменений", this.message, "success");
            this.$router.push({name: 'roles'});  
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при создании новой политика безопасности", "error");
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
                this.$router.push({name: 'roles'});
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
            this.$router.push({name: 'roles'});
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