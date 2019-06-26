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
          <label for="inputUIGroup" class="col-sm-4 control-label">UI рабочей группы</label>
          <div class="col-sm-10">
            <input type="text" v-model="group.slug" class="form-control" id="inputUIGroup" disabled required placeholder="UI рабочей группы">
          </div>
        </div>
                  
        <div class="form-group">
          <label for="inputGroupName" class="col-sm-4 control-label">Имя рабочей группы</label>
          <div class="col-sm-10">
            <input type="text" v-model="group.name" class="form-control" id="inputGroupName" required placeholder="Имя рабочей группы">
          </div>
        </div>
                  
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <div class="form-check" v-for="mission in group.all_missions" :key="mission.id">
              <input type="checkbox" class="form-check-input"
                     v-bind:value="mission.id"
                     v-model="missionsChecked"
                     >
              <label class="form-check-label" for="missionCheckBox">{{ mission.name }}</label>
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
        missionsChecked: []
      }
    },
    created() {
      let uri = `/api/groups/${this.$route.params.id}`;
      this.axios.get(uri).then((response) => {
        this.group = response.data.data;
        this.setMissionsChecked();
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
      updateGroup(/*event*/){
        this.group.missions = this.missionsChecked;
        let uri = `/api/groups/${this.$route.params.id}`;
        this.axios.patch(uri, this.group/*{}*/)
          .then((response) => {
            if(response.data) {
              //this.$emit("changecartevent", 1);
              //swal("Сохранение изменений", "Политика безопасности успешно отредактирована!", "success");
              this.$router.push({name: 'groups'});
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
        setMissionsChecked() {
          for(let i = 0; i < this.group.missions.length; i++) {
            Vue.set(this.missionsChecked, i, this.group.missions[i].id);
    			}
        },
      },
    }
</script>