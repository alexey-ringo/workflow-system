<template>
  <!-- Horizontal Form -->
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Новый процесс для рабочих групп</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="addRoute">
      <div class="card-body">
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Название процесса</label>
          <div class="col-sm-10">
            <input type="text" v-model="route.name" class="form-control" required placeholder="Название задачи">
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <input type="text" id="sequenceBox" v-model="sequenceNum">
              <label>Очередь выполнения процесса</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="checkbox" v-model="superChecked">
              <label>Процесс супервайзера</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="checkbox" v-model="finalChecked">
              <label>Финальный процесс</label>
            </div>
          </div>
        </div>
        
      </div>
      <!-- /.card-body -->
  
      <div class="card-footer">
        <button class="btn btn-primary">Создать</button>
        <router-link :to="{name: 'missions'}" class="btn btn-default float-right">Отмена</router-link>
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
        route:{}
      }
    },
    /*
    create() {
    },
    */
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json';
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
      
      let uri = '/api/routes';
      this.axios.get(uri)
      	.then((response) => {
        	this.routes = response.data.data;
        })
        .catch(e => {
        	swal('Ошибка', "Внутренняя ошибка сервера", "error");
        });
    },
    methods: {
      addRoute(){
        //this.route.sequence = this.sequenceNum;
        //this.mission.is_super = this.superChecked;
        //this.mission.is_final = this.finalChecked;
        let uri = '/api/routes';
        this.axios.post(uri, this.route).then((response) => {
          if(response.data.data) {
            //swal("Заказ", "Ваш заказ принят!", "success");
            this.$router.push({name: 'routes'});
          }
          else {
            swal("Сохранение изменений", "Что то пошло не так...", "error");
            this.$router.push({name: 'routes'});
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
            this.$router.push({name: 'routes'});
          }
        });
      },
    }
  }
</script>