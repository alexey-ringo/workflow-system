<template>
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Редактирование разрешения операции</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="updatePermission">
      <div class="card-body">
        <div class="form-group">
          <label for="inputUIPermission" class="col-sm-4 control-label">UI разрешения операции</label>
          <div class="col-sm-10">
            <input type="text" v-model="permission.slug" class="form-control" id="inputUIPermission" disabled required placeholder="UI роли">
          </div>
        </div>
                  
        <div class="form-group">
          <label for="inputPermissionName" class="col-sm-4 control-label">Название разрешения операции</label>
          <div class="col-sm-10">
            <input type="text" v-model="permission.name" class="form-control" id="inputPermissionName" required placeholder="Название роли">
          </div>
        </div>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button class="btn btn-primary">Применить</button>
        <router-link :to="{name: 'permissions'}" class="btn btn-default float-right">Отмена</router-link>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>
</template>


<script>
  export default {
    data(){
      return {
        permission:{},
      }
    },
    created() {
      let uri = `/api/permissions/${this.$route.params.id}`;
      this.axios.get(uri).then((response) => {
        this.permission = response.data.data;
      });
    },
    mounted() {
      //let uri = `/api/permissions/${this.$route.params.id}`;
      //this.axios.get(uri).then((response) => {
      //  this.permission = response.data.data;
      //});
    },
    methods: {
      updatePermission(/*event*/){
        let uri = `/api/permissions/${this.$route.params.id}`;
        this.axios.patch(uri, this.permission/*{}*/)
          .then((response) => {
            if(response.data) {
              //this.$emit("changecartevent", 1);
              //swal("Сохранение изменений", "Политика безопасности успешно отредактирована!", "success");
              this.$router.push({name: 'permissions'});
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
      },
    }
</script>