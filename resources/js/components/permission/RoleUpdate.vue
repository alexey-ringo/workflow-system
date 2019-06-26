<template>
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Редактирование политики безопасности</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="updateRole">
      <div class="card-body">
        <div class="form-group">
          <label for="inputUIRole" class="col-sm-4 control-label">UI политики безопасности</label>
          <div class="col-sm-10">
            <input type="text" v-model="role.slug" class="form-control" id="inputUIRole" disabled required placeholder="UI политики безопасности">
          </div>
        </div>
                  
        <div class="form-group">
          <label for="inputRoleName" class="col-sm-4 control-label">Имя политики безопасности</label>
          <div class="col-sm-10">
            <input type="text" v-model="role.name" class="form-control" id="inputRoleName" required placeholder="Имя политики безопасности">
          </div>
        </div>
                  
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <div class="form-check" v-for="permission in role.all_permissions" :key="permission.id">
              <input type="checkbox" class="form-check-input"
                     v-bind:value="permission.id"
                     v-model="permissionsChecked"
                     >
              <label class="form-check-label" for="permissionCheckBox">{{ permission.name }}</label>
            </div>
          </div>
        </div>
      </div>
                <!-- /.card-body -->
      <div class="card-footer">
        <button class="btn btn-primary">Применить</button>
        <router-link :to="{name: 'roles'}" class="btn btn-default float-right">Отмена</router-link>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>
</template>


<script>
  export default {
    data(){
      return {
        role:{},
        permissionsChecked: []
      }
    },
    created() {
      let uri = `/api/roles/${this.$route.params.id}`;
      this.axios.get(uri).then((response) => {
        this.role = response.data.data;
        this.setPermissionsChecked();
      });
    },
    mounted() {
      //let uri = `/api/roles/${this.$route.params.id}`;
      //this.axios.get(uri).then((response) => {
      //  this.role = response.data.data;
      //this.setPermissionsChecked();
      //});
    },
    methods: {
      updateRole(){
        this.role.permissions = this.permissionsChecked;
        let uri = `/api/roles/${this.$route.params.id}`;
        this.axios.patch(uri, this.role/*{}*/)
          .then((response) => {
            if(response.data) {
              //this.$emit("changecartevent", 1);
              //swal("Сохранение изменений", "Политика безопасности успешно отредактирована!", "success");
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
        setPermissionsChecked() {
          for(let i = 0; i < this.role.permissions.length; i++) {
            Vue.set(this.permissionsChecked, i, this.role.permissions[i].id);
    			}
        },
      },
    }
</script>