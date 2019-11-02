<template>
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Редактирование пользователя</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="updateUser">
      <div class="card-body">
                  
        <div class="form-group">
          <label for="inputUserName" class="col-sm-4 control-label">Имя пользователя</label>

          <div class="col-sm-10">
            <input type="text" v-model="user.name" class="form-control" id="inputUserName" disabled required placeholder="Имя">
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
            <input type="email" v-model="user.email" class="form-control" id="inputUserEmail" disabled required placeholder="Email">
          </div>
        </div>
                  
        <div class="form-group">
          <label for="inputUserPassword" class="col-sm-4 control-label">Пароль</label>

          <div class="col-sm-10">
            <input type="password" v-model="user.password" class="form-control" id="inputUserPassword" placeholder="Password">
          </div>
        </div>
                  
        <div class="form-group">
          <label for="inputUserPasswordConfirm" class="col-sm-4 control-label">Подтверждение пароля</label>

          <div class="col-sm-10">
            <input type="password" v-model="user.password_confirmation" class="form-control" id="inputUserPasswordConfirm" placeholder="Password">
          </div>
        </div>
                  
        <div class="row">          
          <div class="col-md-6">
            <div class="form-group">
              <h5>Политики безопасности</h5>
              <div class="form-check" v-for="role in user.all_roles" :key="role.id">
                <input type="checkbox" class="form-check-input"
                       v-bind:value="role.id"
                       v-model="rolesChecked"
                       >
                <label class="form-check-label" for="rolesCheckBox">{{ role.title }}</label>
              </div>
            </div>  
          </div>
        
        
          <div class="col-md-6">
            <div class="form-group">
              <h5>Рабочие группы</h5>
              <div class="form-check" v-for="group in user.all_groups" :key="group.id">
                <input type="checkbox" class="form-check-input"
                       v-bind:value="group.id"
                       v-model="groupsChecked"
                       >
                <label class="form-check-label" for="groupsCheckBox">{{ group.title }}</label>
              </div>
            </div>  
          </div>
        </div>
        
      </div>
      <!-- /.card-body -->
      
      <div class="card-footer">
        <button class="btn btn-primary">Применить</button>
        <router-link :to="{name: 'users'}" class="btn btn-default float-right">Отмена</router-link>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>
</template>


<script>
  export default {
    data(){
      return {
        user:{},
        message: '',
        rolesChecked: [],
        groupsChecked: []
      }
    },
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
      
      this.getUpdatedUser();
    },
    methods: {
      getUpdatedUser() {
        let uri = `/api/users/${this.$route.params.id}`;
        this.axios.get(uri).then((response) => {
          if(response.data.data) {
            this.user = response.data.data;
            this.setRolesChecked();
            this.setGroupsChecked();
          }
          else if (response.data.message) {
            this.message = response.data.message;
            swal("Ошибка", this.message, "error");
            this.$router.push({name: 'users'});
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к модифицируему пользователю", "error");
            this.$router.push({name: 'users'});
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
                this.$router.push({name: 'users'});
              }
            }         
          }
          else if(error.request) {
            //console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            console.log('Внутренняя ошибка: ' + error.message);
            this.$router.push({name: 'users'});
          }
        });
      },
      updateUser(){
        this.user.roles = this.rolesChecked;
        this.user.groups = this.groupsChecked;
        let uri = `/api/users/${this.$route.params.id}`;
        this.axios.patch(uri, this.user/*{}*/)
          .then((response) => {
            if(response.data.message) {
              this.message = response.data.message;
              swal("Сохранение изменений", this.message, "success");
              this.$router.push({name: 'users'});
            }
            else {
              swal("Ошибка", "Нет ответа от сервера при сохранении изменений данных пользователя", "error");
              this.$router.push({name: 'users'});
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
                  this.$router.push({name: 'users'});
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
              this.$router.push({name: 'users'});
            }
          });
        },
        setRolesChecked() {
          for(let i = 0; i < this.user.roles.length; i++) {
            Vue.set(this.rolesChecked, i, this.user.roles[i].id);
    			}
        },
        setGroupsChecked() {
          for(let i = 0; i < this.user.groups.length; i++) {
            Vue.set(this.groupsChecked, i, this.user.groups[i].id);
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
      }
    }
</script>