<template>
  <div class="card card-info">
    <div class="card-header">
      <h3 v-if="!isSequenceLast" class="card-title">Обработка заявки</h3>
      <h3 v-if="isSequenceLast" class="card-title">Завершение заявки</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="form-horizontal">
      <div class="card-body">
        <div class="form-group">
          <label for="inputTaskName" class="col-sm-4 control-label">Название заявки</label>
          <div class="col-sm-10">
            <input type="text" v-model="task.title" class="form-control" id="inputTaskName" disabled placeholder="Название заявки">
          </div>
        </div>
                  
        <div class="form-group">
          <label for="inputTaskDesc" class="col-sm-4 control-label">Краткое описание</label>
          <div class="col-sm-10">
            <input type="text" v-model="task.description" class="form-control" id="inputTaskDesc" disabled placeholder="Краткое описание">
          </div>
        </div>
                  
        
      </div>
                <!-- /.card-body -->
      <div class="card-footer">
        <button name="prev" class="btn btn-primary" v-if="!isSequenceFirst" @click="updateTaskToPrev">Предидущая</button>
        <button name="next" class="btn btn-primary" v-if="!isSequenceLast" @click="updateTaskToNext">Следующая</button>
        <button name="close" class="btn btn-primary" v-if="isSequenceLast" @click="updateTaskToClose">Закрыть</button>
        <router-link name="exit" :to="{name: 'tasks'}" class="btn btn-default float-right">Отмена</router-link>
      </div>
      <!-- /.card-footer -->
    </div>
  </div>
</template>


<script>
  export default {
    data(){
      return {
        task:{},
        
      }
    },
    created() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
      
      let uri = `/api/tasks/${this.$route.params.id}`;
      this.axios.get(uri).then((response) => {
        this.task = response.data.data;
      });
    },
    mounted() {
      //let uri = `/api/groups/${this.$route.params.id}`;
      //this.axios.get(uri).then((response) => {
      //  this.group = response.data.data;
      //this.setMissionsChecked();
      //});
    },
    methods: {
      updateTaskToNext(){
        this.task.execMissionId = this.task.nextMissionId;
        this.task.execMissionName = this.task.nextMissionName;
        this.task.destination = 1;
        let uri = `/api/tasks/${this.$route.params.id}`;
        this.axios.patch(uri, this.task/*{}*/)
          .then((response) => {
            if(response.data.data) {
              //this.$emit("changecartevent", 1);
              //swal("Сохранение изменений", "Политика безопасности успешно отредактирована!", "success");
              this.$router.push({name: 'tasks'});
            }
            else {
            	swal("Сохранение изменений", "Что то пошло не так...", "error");
            	this.$router.push({name: 'tasks'});
            }
          })
          .catch(e => {
          	if(e == 'Error: Request failed with status code 401') {
              if (localStorage.getItem('jwt')) {
                localStorage.removeItem('jwt');
                this.$router.push({name: 'login'});
              }
              //swal('Ошибка аутентификации', "Ползователь не зарегистрирован", "error");
            }
            else {
              swal('Ошибка', "Внутренняя ошибка сервера", "error");
              this.$router.push({name: 'tasks'});
            }
          });
      },
      updateTaskToPrev(){
        this.task.execMissionId = this.task.prevMissionId;
        this.task.execMissionName = this.task.prevMissionName;
        this.task.destination = 2;
        let uri = `/api/tasks/${this.$route.params.id}`;
        this.axios.patch(uri, this.task/*{}*/)
          .then((response) => {
            if(response.data.data) {
              //this.$emit("changecartevent", 1);
              //swal("Сохранение изменений", "Политика безопасности успешно отредактирована!", "success");
              this.$router.push({name: 'tasks'});
            }
            else {
          	  swal("Сохранение изменений", "Что то пошло не так...", "error");
          	  this.$router.push({name: 'tasks'});
            }
          })
          .catch(e => {
        	  if(e == 'Error: Request failed with status code 401') {
              if (localStorage.getItem('jwt')) {
                localStorage.removeItem('jwt');
                this.$router.push({name: 'login'});
              }
              //swal('Ошибка аутентификации', "Ползователь не зарегистрирован", "error");
            }
            else {
              swal('Ошибка', "Внутренняя ошибка сервера", "error");
              this.$router.push({name: 'tasks'});
            }
          });
      },
      updateTaskToClose(){
        this.task.execMissionId = this.task.nextMissionId;
        this.task.execMissionName = this.task.nextMissionName;
        this.task.destination = 3;
        let uri = `/api/tasks/${this.$route.params.id}`;
        this.axios.patch(uri, this.task/*{}*/)
          .then((response) => {
            if(response.data.data) {
              //this.$emit("changecartevent", 1);
              //swal("Сохранение изменений", "Политика безопасности успешно отредактирована!", "success");
              this.$router.push({name: 'tasks'});
            }
            else {
          	  swal("Сохранение изменений", "Что то пошло не так...", "error");
          	  this.$router.push({name: 'tasks'});
            }
          })
          .catch(e => {
        	  if(e == 'Error: Request failed with status code 401') {
              if (localStorage.getItem('jwt')) {
                localStorage.removeItem('jwt');
                this.$router.push({name: 'login'});
              }
              //swal('Ошибка аутентификации', "Ползователь не зарегистрирован", "error");
            }
            else {
              swal('Ошибка', "Внутренняя ошибка сервера", "error");
              this.$router.push({name: 'tasks'});
            }
          });
      },
    },
    computed: {
      isSequenceLast() {
        return this.task.isSequenceLast;
      },
      isSequenceFirst() {
        return this.task.isSequenceFirst;
      }
    }
  }
</script>