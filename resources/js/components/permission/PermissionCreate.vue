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
            <input type="text" v-model="permission.title" class="form-control" id="inputPermission" required disabled placeholder="Название разрешения">
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
        permission:{},
        message: '',
        permissionNames: [],
        selectPermissionName: false,
      }
    },
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json';
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
      
      this.getPermissionNames();
    },
    methods: {
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
            //console.log('Внутренняя ошибка: ' + error.message);
            this.$router.push({name: 'processes'});
          }
        });
      },
      addPermission(){
        this.permission.name = this.selectPermissionName;
        
        let uri = '/api/permissions';
        this.axios.post(uri, this.permission).then((response) => {
          if(response.data.message) {
            this.message = response.data.message;                            
            swal("Сохранение изменений", this.message, "success");
            this.$router.push({name: 'permissions'});  
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при создании нового разрешения операций", "error");
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
            this.$router.push({name: 'permissions'});
          }
        });
      },
      onPermissionNameSelect(event) {        
        this.permission.title = event.target.selectedOptions[0].text;
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