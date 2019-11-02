<template>
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Редактирование рабочей группы участников БП</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="updateGroup">
      <div class="card-body">
        <div class="form-group">
          <label for="inputGroupName" class="col-sm-4 control-label">Имя рабочей группы</label>
          <div class="col-sm-10">
            <input type="text" v-model="group.title" class="form-control" id="inputGroupName" required placeholder="Имя рабочей группы">
          </div>
        </div>
                  
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <div class="form-check" v-for="process in group.all_processes" :key="process.id">
              <input type="checkbox" class="form-check-input"
                     v-bind:value="process.id"
                     v-model="processesChecked"
                     >
              <label class="form-check-label" for="processCheckBox">{{ process.title }}</label>
            </div>
          </div>
        </div>
      </div>
                <!-- /.card-body -->
      <div class="card-footer">
        <button class="btn btn-primary">Применить</button>
        <router-link :to="{name: 'groups'}" class="btn btn-default float-right">Отмена</router-link>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>
</template>


<script>
  export default {
    data(){
      return {
        group:{},
        message: '',
        processesChecked: []
      }
    },
    /*
    created() {
    },
    */
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
      
      this.getUpdatedGroup();
    },
    methods: {
      getUpdatedGroup() {
        let uri = `/api/groups/${this.$route.params.id}`;
        this.axios.get(uri).then((response) => {
          if(response.data.data) {
            this.group = response.data.data;
            this.setProcessesChecked();
          }
          else if (response.data.message) {
            this.message = response.data.message;
            swal("Ошибка", this.message, "error");
            this.$router.push({name: 'groups'});
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к модифицируемой рабочей группе", "error");
            this.$router.push({name: 'groups'});
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
            }         
          }
          else if(error.request) {
            //console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            console.log('Внутренняя ошибка: ' + error.message);
            this.$router.push({name: 'groups'});
          }
        });
      },
      updateGroup(/*event*/){
        this.group.processes = this.processesChecked;
        let uri = `/api/groups/${this.$route.params.id}`;
        this.axios.patch(uri, this.group/*{}*/)
          .then((response) => {
            if(response.data.message) {
              this.message = response.data.message;
              swal("Сохранение изменений", this.message, "success");
              this.$router.push({name: 'groups'});
            }
            else {
              swal("Ошибка", "Нет ответа от сервера при сохранении изменений в рабочей группе", "error");
              this.$router.push({name: 'groups'});
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
              //console.log(error.request.data);
            }
            else {
              swal('Ошибка', "Внутренняя ошибка сервера", "error");
              console.log('Внутренняя ошибка: ' + error.message);
              this.$router.push({name: 'groups'});
            }
          });
        },
        setProcessesChecked() {
          for(let i = 0; i < this.group.processes.length; i++) {
            Vue.set(this.processesChecked, i, this.group.processes[i].id);
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