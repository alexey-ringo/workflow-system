<template>
  <!-- Horizontal Form -->
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Новый процесс для рабочих групп</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="addMission">
      <div class="card-body">
        
        <div class="form-group">
          <label for="inputMission" class="col-sm-4 control-label">Название процесса</label>
          <div class="col-sm-10">
            <input type="text" v-model="mission.name" class="form-control" id="inputMission" required placeholder="Название задачи">
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <input type="text" id="sequenceBox" v-model="sequenceNum">
              <label for="sequenceBox">Очередь выполнения процесса</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="checkbox" id="superCheckbox" v-model="superChecked">
              <label for="checkbox">Процесс супервайзера</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="checkbox" id="finalCheckbox" v-model="finalChecked">
              <label for="checkbox">Финальный процесс</label>
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
        mission:{},
        sequenceNum: '',
        superChecked: false,
        finalChecked: false
      }
    },
    /*
    create() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json';
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
      
      let uri = '/api/missions';
      this.axios.get(uri)
      	.then((response) => {
        	this.missions = response.data.data;
        })
        .catch(e => {
        	swal('Ошибка', "Внутренняя ошибка сервера", "error");
        });
    },
    */
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json';
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
      
      let uri = '/api/missions';
      this.axios.get(uri)
      	.then((response) => {
        	this.missions = response.data.data;
        })
        .catch(e => {
        	swal('Ошибка', "Внутренняя ошибка сервера", "error");
        });
    },
    methods: {
      addMission(){
        this.mission.sequence = this.sequenceNum;
        this.mission.is_super = this.superChecked;
        this.mission.is_final = this.finalChecked;
        let uri = '/api/missions';
        this.axios.post(uri, this.mission).then((response) => {
          if(response.data) {
            this.$router.push({name: 'missions'});
          }
          else {
          
          }
        })
        .catch(e => {
          //console.log(e);
          swal('Ошибка', "Внутренняя ошибка сервера", "error");
        });
      },
    }
  }
</script>