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
          <label for="inputRoleName" class="col-sm-4 control-label">Название политики безопасности</label>
          <div class="col-sm-10">
            <input type="text" v-model="role.name" class="form-control" id="inputRoleName" required placeholder="Название политики безопасности">
          </div>
        </div>
                  
        <div class="form-group">
          <label for="inputUIRole" class="col-sm-4 control-label">UI политики безопасности</label>
          <div class="col-sm-10">
            <input type="text" v-model="role.slug" class="form-control" id="inputUIRole" required placeholder="UI политики безопасности">
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
        role:{}
      }
    },
    methods: {
      addRole(){
        let uri = '/api/roles';
        this.axios.post(uri, this.role).then((response) => {
          if(response.data) {
            //swal("Заказ", "Ваш заказ принят!", "success");
            this.$router.push({name: 'roles'});
          }
          else {
            swal("Сохранение изменений", "Что то пошло не так...", "error");
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