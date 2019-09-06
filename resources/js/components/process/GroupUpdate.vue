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
            <div class="form-check" v-for="process in group.all_processes" :key="process.id">
              <input type="checkbox" class="form-check-input"
                     v-bind:value="process.id"
                     v-model="processesChecked"
                     >
              <label class="form-check-label" for="processCheckBox">{{ process.name }}</label>
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
        processesChecked: []
      }
    },
    created() {
      let uri = `/api/groups/${this.$route.params.id}`;
      this.axios.get(uri).then((response) => {
        this.group = response.data.data;
        this.setProcessesChecked();
      });
    },
    mounted() {
      //let uri = `/api/groups/${this.$route.params.id}`;
      //this.axios.get(uri).then((response) => {
      //  this.group = response.data.data;
      //this.setProcessesChecked();
      //});
    },
    methods: {
      updateGroup(/*event*/){
        this.group.processes = this.processesChecked;
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
        setProcessesChecked() {
          for(let i = 0; i < this.group.processes.length; i++) {
            Vue.set(this.processesChecked, i, this.group.processes[i].id);
    			}
        },
      },
    }
</script>