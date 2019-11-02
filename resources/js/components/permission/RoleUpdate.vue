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
          <label for="inputRoleName" class="col-sm-4 control-label">Имя политики безопасности</label>
          <div class="col-sm-10">
            <input type="text" v-model="role.title" class="form-control" id="inputRoleName" required placeholder="Имя политики безопасности">
          </div>
        </div>
                  
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <div class="form-check" v-for="permission in role.all_permissions" :key="permission.id">
              <input type="checkbox" class="form-check-input"
                     v-bind:value="permission.id"
                     v-model="permissionsChecked"
                     >
              <label class="form-check-label" for="permissionCheckBox">{{ permission.title }}</label>
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
        message: '',
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
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
      
      this.getUpdatedRole();
    },
    methods: {
      getUpdatedRole() {
        let uri = `/api/roles/${this.$route.params.id}`;
        this.axios.get(uri).then((response) => {
          if(response.data.data) {
            this.role = response.data.data;
            this.setPermissionsChecked();
          }
          else if (response.data.message) {
            this.message = response.data.message;
            swal("Ошибка", this.message, "error");
            this.$router.push({name: 'roles'});
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к модифицируемой политике безопасности", "error");
            this.$router.push({name: 'roles'});
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
            }         
          }
          else if(error.request) {
            //console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            console.log('Внутренняя ошибка: ' + error.message);
            this.$router.push({name: 'roles'});
          }
        });
      },
      updateRole(){
        this.role.permissions = this.permissionsChecked;
        let uri = `/api/roles/${this.$route.params.id}`;
        this.axios.patch(uri, this.role/*{}*/)
          .then((response) => {
            if(response.data.message) {
              this.message = response.data.message;
              swal("Сохранение изменений", this.message, "success");
              this.$router.push({name: 'roles'});
            }
            else {
              swal("Ошибка", "Нет ответа от сервера при сохранении изменений в рабочей группе", "error");
              this.$router.push({name: 'roles'});
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
                  this.$router.push({name: 'groups'});
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
              this.$router.push({name: 'roles'});
            }
          });
        },
        setPermissionsChecked() {
          for(let i = 0; i < this.role.permissions.length; i++) {
            Vue.set(this.permissionsChecked, i, this.role.permissions[i].id);
    			}
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