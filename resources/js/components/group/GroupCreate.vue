<template>
  <!-- Horizontal Form -->
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Новая рабочая группа</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="addGroup">
      <div class="card-body">
        <div class="form-group">
          <label for="inputGroupName" class="col-sm-4 control-label">Название рабочей группы</label>
          <div class="col-sm-10">
            <input type="text" v-model="group.name" class="form-control" id="inputGroupName" required placeholder="Название рабочей группы">
          </div>
        </div>
                  
        <div class="form-group">
          <label for="inputUIGroup" class="col-sm-4 control-label">UI рабочей группы</label>
          <div class="col-sm-10">
            <input type="text" v-model="group.slug" class="form-control" id="inputUIGroup" required placeholder="UI рабочей группы">
          </div>
        </div>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button class="btn btn-primary">Создать</button>
          <router-link :to="{name: 'groups'}" class="btn btn-default float-right">Отмена</router-link>
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
        group:{}
      }
    },
    methods: {
      addGroup(){
        let uri = '/api/groups';
        this.axios.post(uri, this.group).then((response) => {
          if(response.data) {
            //swal("Заказ", "Ваш заказ принят!", "success");
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
    }
  }
</script>