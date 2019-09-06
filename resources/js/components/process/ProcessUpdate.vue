<template>
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Редактирование процесса обработки обращений клиентов</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="updateProcess">
      <div class="card-body">
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Маршрут обращений</label>
          <div class="col-sm-10">
            <select v-model="selectRoute" required>
              <option v-for="route in routes" :value="route.id" :key="route.id">
                {{ route.name }}
              </option>
            </select>
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Название процесса</label>
          <div class="col-sm-10">
            <input type="text" v-model="process.name" class="form-control" placeholder="Название процесса обработки обращения">
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Уникальный slug процесса</label>
          <div class="col-sm-10">
            <input type="text" v-model="process.slug" class="form-control" disabled placeholder="Уникальный slug процесса обработки обращения">
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Очередь выполнения процесса</label>
              <input type="text" v-model="process.sequence" class="form-control" required>
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
        <button class="btn btn-primary">Применить</button>
        <router-link :to="{name: 'processes'}" class="btn btn-default float-right">Отмена</router-link>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>
</template>


<script>
  export default {
    data(){
      return {
        process: {},
        routes: [],
        selectRoute: null,
        superChecked: false,
        finalChecked: false,
      }
    },
    created() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
      
      let uri = '/api/routes';
      this.axios.get(uri)
      	.then((response) => {
        	this.routes = response.data.data;
        	this.getProcess();
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
            this.$router.push({name: 'processes'});
          }
        });
    },
    mounted() {
      //let uri = `/api/processes/${this.$route.params.id}`;
      //this.axios.get(uri).then((response) => {
      //  this.process = response.data.data;
      //});
    },
    methods: {
      getProcess() {
        let uri = `/api/processes/${this.$route.params.id}`;
        this.axios.get(uri).then((response) => {
          this.process = response.data.data;
          this.setRouteSelected();
          this.setSuperChecked();
          this.setFinalChecked();
        })
        .catch(e => {
          //console.log(e);
          swal('Ошибка', "Внутренняя ошибка сервера", "error");
          
        });
      },
      updateProcess(/*event*/){
        if(this.superChecked) {
          this.process.is_super = 1;
        }
        else {
          this.process.is_super = null;
        }
        
        if(this.finalChecked) {
          this.process.is_final = 1;
        }
        else {
          this.process.is_final = null;
        }
        
        this.process.route_id = this.selectRoute;
        
        let uri = `/api/processes/${this.$route.params.id}`;
        this.axios.patch(uri, this.process/*{}*/)
          .then((response) => {
            if(response.data) {
              //this.$emit("changecartevent", 1);
              this.$router.push({name: 'processes'});
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
              this.$router.push({name: 'processes'});
            }
          });
        },
        setRouteSelected() {
          this.selectRoute = this.process.route_id;
        },
        //setSequenceSelected() {
        //  this.sequenceNum = this.process.sequence;
        //},
        setSuperChecked() {
          this.superChecked = this.process.is_super;
        },
        setFinalChecked() {
          this.finalChecked = this.process.is_final;
        },
      },
    }
</script>