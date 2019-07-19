<template>
<!-- Horizontal Form -->
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Новый пользователь</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="addUser">
      <div class="card-body">
        <div class="form-group">
          <label for="inputUserName" class="col-sm-4 control-label">Имя пользователя</label>
          <div class="col-sm-10">
            <input type="text" v-model="user.name" class="form-control" id="inputUserName" required placeholder="Имя">
          </div>
        </div>
        
        <div class="form-group">
          <label for="inputUserPhone" class="col-sm-4 control-label">Номер телефона</label>
          <div class="col-sm-10">
            <input type="text" v-model="user.phone" class="form-control" id="inputUserPhone" placeholder="В формате 7xxxxxxxxxx (10 знаков после семерки)">
          </div>
        </div>

        <div class="form-group">
          <label for="inputUserEmail" class="col-sm-4 control-label">Email</label>
          <div class="col-sm-10">
            <input type="email" v-model="user.email" class="form-control" id="inputUserEmail" required placeholder="Email">
          </div>
        </div>

        <div class="form-group">
          <label for="inputUserPassword" class="col-sm-4 control-label">Пароль</label>
          <div class="col-sm-10">
            <input type="password" v-model="user.password" class="form-control" id="inputUserPassword" required placeholder="Password">
          </div>
        </div>
                  
        <div class="form-group">
          <label for="inputUserPasswordConfirm" class="col-sm-4 control-label">Подтверждение пароля</label>
          <div class="col-sm-10">
            <input type="password" v-model="user.password_confirmation" class="form-control" id="inputUserPasswordConfirm" required placeholder="Confirm Password">
          </div>
        </div>
        
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button class="btn btn-primary">Создать</button>
        <router-link :to="{name: 'users'}" class="btn btn-default float-right">Отмена</router-link>
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
        user:{}
      }
    },
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
    },
    methods: {
      addUser(){
        let uri = '/api/users';
        this.axios.post(uri, this.user).then((response) => {
          if(response.data) {
            this.$router.push({name: 'users'});
          }
          else {
            swal("Создание нового пользователя", "Что то пошло не так...", "error");
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
          }
        });
      },
    }
  }
</script>