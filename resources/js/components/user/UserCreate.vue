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
    methods: {
      addUser(){
        let uri = '/api/users';
        this.axios.post(uri, this.user).then((response) => {
          if(response.data) {
            //swal("Заказ", "Ваш заказ принят!", "success");
            this.$router.push({name: 'users'});
          }
          else {
          
          }
        })
        .catch(e => {
          //console.log(e);
          swal('Ошибка', "Внутренняя ошибка сервера", "error");
        });
      },
    }
  }
</script>