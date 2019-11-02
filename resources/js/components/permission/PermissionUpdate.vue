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
          <label for="inputPermissionName" class="col-sm-4 control-label">Название разрешения операции</label>
          <div class="col-sm-10">
            <input type="text" v-model="permission.title" class="form-control" id="inputPermissionName" required placeholder="Название роли">
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">UI разрешения операции</label>
          <div class="col-sm-10">
            <select v-model="selectPermissionName"  @change="onPermissionNameSelect($event)" required>
              <option v-for="(permissionName, index) in permissionNames" :value="permissionName" :key="index">
                {{ index }}
              </option>
            </select>
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
        message: '',
        permissionNames: [],
        selectPermissionName: false,
      }
    },
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
      
      this.getUpdatedPermission();
      this.getPermissionNames();
    },
    methods: {
      getUpdatedPermission() {
        let uri = `/api/permissions/${this.$route.params.id}`;
        this.axios.get(uri).then((response) => {
          if(response.data.data) {
            this.permission = response.data.data;
            this.setPermissionNameSelected();
          }
          else if (response.data.message) {
            this.message = response.data.message;
            swal("Ошибка", this.message, "error");
            this.$router.push({name: 'processes'});
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к модифицируемому процессу", "error");
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
      getPermissionNames() {
        let uri = '/api/frontrequest-permission-names';
        this.axios.get(uri).then((response) => {
          if(response.data.data) {
            this.permissionNames = response.data.data;
          }
          else if (response.data.message) {
            this.message = response.data.message;
            swal("Ошибка", this.message, "error");
            this.$router.push({name: 'processes'});
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к списку имен разрешений", "error");
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
      updatePermission(/*event*/){
        this.permission.name = this.selectPermissionName;

        let uri = `/api/permissions/${this.$route.params.id}`;
        this.axios.patch(uri, this.permission/*{}*/)
          .then((response) => {
            if(response.data.message) {
              this.message = response.data.message;
              swal("Сохранение изменений", this.message, "success");
              this.$router.push({name: 'permissions'});
            }
            else {
              swal("Ошибка", "Нет ответа от сервера при сохранении изменений в разрешении операции", "error");
              this.$router.push({name: 'permissions'});
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
                  this.$router.push({name: 'permissions'});
                }
              }         
            }
            else if(error.request) {
              //console.log(error.request.data);
            }
            else {
              swal('Ошибка', "Внутренняя ошибка сервера", "error");
              //console.log('Внутренняя ошибка: ' + error.message);
              this.$router.push({name: 'permissions'});
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
        onPermissionNameSelect(event) {        
          this.permission.title = event.target.selectedOptions[0].text;
        },
        setPermissionNameSelected() {
          this.selectPermissionName = this.permission.name;
        },
      },
    }
</script>