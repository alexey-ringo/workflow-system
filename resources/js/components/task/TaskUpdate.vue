<template>
  <div class="card card-info">
    <div class="card-header">
      <h3 v-if="!isSequenceLast" class="card-title">Обработка заявки</h3>
      <h3 v-if="isSequenceLast" class="card-title">Завершение заявки</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="updateTaskToNext">
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
        <button class="btn btn-primary" v-if="!isSequenceLast">Следующая</button>
        
        <router-link :to="{name: 'tasks'}" class="btn btn-default float-right">Отмена</router-link>
      </div>
      <!-- /.card-footer -->
    </form>
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
        let uri = `/api/tasks/${this.$route.params.id}`;
        this.axios.patch(uri, this.task/*{}*/)
          .then((response) => {
            if(response.data) {
              //this.$emit("changecartevent", 1);
              //swal("Сохранение изменений", "Политика безопасности успешно отредактирована!", "success");
              this.$router.push({name: 'tasks'});
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
      updateTaskToPrev(){
        this.task.execMissionId = this.task.prevMissionId;
        this.task.execMissionName = this.task.prevMissionName;
        let uri = `/api/tasks/${this.$route.params.id}`;
        this.axios.patch(uri, this.task/*{}*/)
          .then((response) => {
            if(response.data) {
              //this.$emit("changecartevent", 1);
              //swal("Сохранение изменений", "Политика безопасности успешно отредактирована!", "success");
              this.$router.push({name: 'tasks'});
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
      updateTaskClose(){
      let uri = `/api/tasks/${this.$route.params.id}`;
      this.axios.patch(uri, this.task/*{}*/)
        .then((response) => {
          if(response.data) {
            //this.$emit("changecartevent", 1);
            //swal("Сохранение изменений", "Политика безопасности успешно отредактирована!", "success");
            this.$router.push({name: 'tasks'});
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
    computed: {
      isSequenceLast() {
        return this.task.isSequenceLast;
      }
    }
  }
</script>