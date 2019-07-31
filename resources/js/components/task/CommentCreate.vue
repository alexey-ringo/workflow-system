<template>
  <!-- Horizontal Form -->
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Новая заявка</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="createTask">
      <div class="card-body">
        <div class="form-group">
          <label for="inputTaskName" class="col-sm-4 control-label">Название заявки</label>
          <div class="col-sm-10">
            <input type="text" v-model="task.title" class="form-control" id="inputTaskName" required placeholder="Название заявки">
          </div>
        </div>
                  
        <div class="form-group">
          <label for="inputTaskDesc" class="col-sm-4 control-label">Краткое описание</label>
          <div class="col-sm-10">
            <input type="text" v-model="task.description" class="form-control" id="inputTaskDesc" required placeholder="Краткое описание">
          </div>
        </div>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button class="btn btn-primary">Создать</button>
          <router-link :to="{name: 'tasks'}" class="btn btn-default float-right">Отмена</router-link>
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
        task:{}
      }
    },
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
    },
    methods: {
      createTask(){
        let uri = '/api/tasks';
        this.axios.post(uri, this.task).then((response) => {
          if(response.data.data) {
            //swal("Заказ", "Ваш заказ принят!", "success");
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
    }
  }
</script>