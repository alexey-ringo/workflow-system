<template>
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Редактирование маршрута для обработки клиентских заявок</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="updateRoute">
      <div class="card-body">
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Название маршрута</label>
          <div class="col-sm-10">
            <input type="text" v-model="route.name" class="form-control" required placeholder="Название маршрута">
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Идентификатор маршрута</label>
          <div class="col-sm-10">
            <input type="text" v-model="route.value" class="form-control" required placeholder="id-маршрута">
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Описание маршрута</label>
          <div class="col-sm-10">
            <input type="text" v-model="route.description" class="form-control" placeholder="Описание маршрута">
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="form-group">
            <input type="checkbox" v-model="routeStatus">
            <label>Маршрут используется</label>
          </div>
        </div>
        
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button class="btn btn-primary">Применить</button>
        <router-link :to="{name: 'routes'}" class="btn btn-default float-right">Отмена</router-link>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>
</template>


<script>
  export default {
    data(){
      return {
        route: {},
        routeStatus: false
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
      
      let uri = `/api/routes/${this.$route.params.id}`;
      this.axios.get(uri).then((response) => {
        this.route = response.data.data;
        this.setRouteStatus();
      })
      .catch(e => {
        //console.log(e);
        if(e == 'Error: Request failed with status code 401') {
          if (localStorage.getItem('jwt')) {
            localStorage.removeItem('jwt');
            this.$router.push({name: 'login'});
          }
          //swal('Ошибка аутентификации', "Ползователь не зарегистрирован", "error");
        }
        else {
          swal('Ошибка', "Внутренняя ошибка сервера", "error");
        }
      });
    },
    methods: {
      updateRoute(/*event*/){
        if(this.routeStatus) {
          this.route.in_use = 1;
        }
        else {
          this.route.in_use = null;
        }
        
        let uri = `/api/routes/${this.$route.params.id}`;
        this.axios.patch(uri, this.route/*{}*/)
          .then((response) => {
            if(response.data.data) {
              //this.$emit("changecartevent", 1);
              this.$router.push({name: 'routes'});
            }
            else {
            	swal("Сохранение изменений", "Что то пошло не так...", "error");
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
        setRouteStatus() {
          this.routeStatus = this.route.in_use;
        },
      },
    }
</script>