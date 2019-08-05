<template>
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Обработка заявки</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="form-horizontal">
      <div class="card-body">
        <div class="form-group">
          <label for="inputTaskName" class="col-sm-4 control-label">Название заявки</label>
          <div class="col-sm-10">
            
          </div>
        </div>
                  
        <div class="form-group">
          <textarea class="form-control" v-model="comment.comment" readonly style="height: 300px">
                      
          </textarea>
        </div>
                  
        
      </div>
                <!-- /.card-body -->
      <div class="card-footer">
        <router-link name="exit" :to="{name: 'task-update', params: {id: this.comment.task_id} }" class="btn btn-default float-right">Отмена</router-link>
      </div>
      <!-- /.card-footer -->
    </div>
  </div>
</template>


<script>
  export default {
    data(){
      return {
        comment: {},
        
      }
    },
    created() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
      
      let uri = `/api/comments/${this.$route.params.commid}`;
      this.axios.get(uri).then((response) => {
        this.comment = response.data.data;
      });
    },
    mounted() {
    },
    methods: {
    },
  }
</script>