<template>
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Редактирование процесса для рабочих групп</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="updateMission">
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
              <input type="text" id="sequenceBox" v-model="sequenceNum" disabled>
              <label for="sequenceBox">Очередь выполнения процесса</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="checkbox" id="superCheckbox" v-model="superChecked">
              <label for="superCheckbox">Процесс супервайзера</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="checkbox" id="finalCheckbox" v-model="finalChecked">
              <label for="finalCheckbox">Финальный процесс</label>
            </div>
          </div>
        </div>
        
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button class="btn btn-primary">Применить</button>
        <router-link :to="{name: 'missions'}" class="btn btn-default float-right">Отмена</router-link>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>
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
    created() {
      
    },
    */
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
      
      let uri = `/api/missions/${this.$route.params.id}`;
      this.axios.get(uri).then((response) => {
        this.mission = response.data.data;
        this.setSequenceSelected();
        this.setSuperChecked();
        this.setFinalChecked();
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
      updateMission(/*event*/){
        this.mission.sequence = this.sequenceNum;
        this.mission.is_super = this.superChecked;
        this.mission.is_final = this.finalChecked;
        console.log(this.mission);
        let uri = `/api/missions/${this.$route.params.id}`;
        this.axios.patch(uri, this.mission/*{}*/)
          .then((response) => {
            if(response.data) {
              //this.$emit("changecartevent", 1);
              this.$router.push({name: 'missions'});
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
        setSequenceSelected() {
          this.sequenceNum = this.mission.sequence;
        },
        setSuperChecked() {
          this.superChecked = this.mission.is_super;
        },
        setFinalChecked() {
          this.finalChecked = this.mission.is_final;
        },
      },
    }
</script>