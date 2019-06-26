<template>
  <!-- Horizontal Form -->
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Новое разрешение операций</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="addPermission">
      <div class="card-body">
        <div class="form-group">
          <label for="inputPermission" class="col-sm-4 control-label">Название разрешения</label>
          <div class="col-sm-10">
            <input type="text" v-model="permission.name" class="form-control" id="inputPermission" required placeholder="Название разрешения">
          </div>
        </div>
                  
        <div class="form-group">
          <label for="inputUIPermission" class="col-sm-4 control-label">UI разрешения</label>
          <div class="col-sm-10">
            <input type="text" v-model="permission.slug" class="form-control" id="inputUIPermission" required placeholder="UI разрешения">
          </div>
        </div>
      </div>
      <!-- /.card-body -->
  
      <div class="card-footer">
        <button class="btn btn-primary">Создать</button>
        <router-link :to="{name: 'permissions'}" class="btn btn-default float-right">Отмена</router-link>
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
        permission:{}
      }
    },
    methods: {
      addPermission(){
        let uri = '/api/permissions';
        this.axios.post(uri, this.permission).then((response) => {
          if(response.data) {
            //swal("Заказ", "Ваш заказ принят!", "success");
            this.$router.push({name: 'permissions'});
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