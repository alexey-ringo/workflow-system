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
              <h6>Очередь выполнения процесса</h6>
              <select v-model="queueSelected">
                <option disabled value="">Порядковый номер процесса</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
                <option>6</option>
                <option>7</option>
              </select>
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
        queueSelected: '',
        superChecked: false,
        finalChecked: false
      }
    },
    created() {
      let uri = `/api/missions/${this.$route.params.id}`;
      this.axios.get(uri).then((response) => {
        this.mission = response.data.data;
        this.setQueueSelected();
        this.setSuperChecked();
        this.setFinalChecked();
      });
    },
    mounted() {
      //let uri = `/api/missions/${this.$route.params.id}`;
      //this.axios.get(uri).then((response) => {
      //  this.mission = response.data.data;
      //});
    },
    methods: {
      updateMission(/*event*/){
        this.mission.queue = this.queueSelected;
        this.mission.is_super = this.superChecked;
        this.mission.is_final = this.finalChecked;
        console.log(this.mission);
        let uri = `/api/missions/${this.$route.params.id}`;
        this.axios.patch(uri, this.mission/*{}*/)
          .then((response) => {
            if(response.data) {
              //this.$emit("changecartevent", 1);
              //swal("Сохранение изменений", "Политика безопасности успешно отредактирована!", "success");
              this.$router.push({name: 'missions'});
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
        setQueueSelected() {
          this.queueSelected = this.mission.queue;
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