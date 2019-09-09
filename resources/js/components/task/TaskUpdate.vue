<template>
  <div class="card card-info">
    <div class="card-header">
      <div class="row">
        <div class="col-md-8">
          <h3 v-if="!isSequenceLast" class="card-title">Обработка заявки № <b>{{task.task}}</b>
                                                         в очереди: <b>{{task.sequenceName}}</b></h3>
          <h3 v-if="isSequenceLast" class="card-title">Завершение заявки № {{task.task}}</h3>
        </div>
      </div>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="form-horizontal">
      <div class="card-body">
        <div class="form-group">
          <label class="col-sm-4 control-label">Название заявки:</label>
          <label>{{task.title}}</label>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Краткое описание:</label>
          <label>{{task.description}}</label>
        </div>
        
        <div class="form-group" v-if="visibleCommentCreate">
          <textarea class="form-control" v-model="comment.comment" placeholder="введите несколько строчек" style="height: 300px">
                      
          </textarea>
        </div>
        
        <div class="table-responsive mailbox-messages" v-if="!visibleCommentCreate">
          <table class="table table-hover table-striped">
            <tbody>
              <tr v-for="(commentItem, commentId) in comments" :key="commentItem.id" @dblclick="showComment(commentId)">
                <td class="mailbox-date">5 mins ago</td>
                <td class="mailbox-subject">{{commentItem.task.process_name}}</td>
                <td class="mailbox-name">{{commentItem.user_name}}</td>
                <td class="mailbox-subject">{{commentItem.comment}}</td>
                <td class="mailbox-attachment"></td>
              </tr>
            </tbody>
          </table>
          <!-- /.comments-table -->
        </div>       
        
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <div v-if="visibleCommentCreate">
          <button class="btn btn-primary" v-if="!isSequenceFirst" @click="storeNewComment(2)">Назад в <b>{{task.prevProcessName}}</b></button>
          <button class="btn btn-primary" v-if="!isSequenceLast" @click="storeNewComment(1)">Вперед в <b>{{task.nextProcessName}}</b></button>
          <button class="btn btn-primary" @click="storeNewComment(4)">Просто комментарий</button>
          <button class="btn btn-primary" v-if="isSequenceLast" @click="storeNewComment(3)">Закрыть заявку</button>
          <a href="#" class="btn btn-default float-right" v-on:click="closeNewComment">Отмена</a>
        </div>
        <div v-if="!visibleCommentCreate">
          <router-link name="exit" :to="{name: 'tasks'}" class="btn btn-default float-right">Отмена</router-link>
          <a href="#" class="btn btn-primary" v-on:click="createNewComment">Комментарий по задаче</a>
        </div>
      </div>
      <!-- /.card-footer -->
    </div>
  </div>
</template>


<script>
  export default {
    data(){
      return {
        task: {
          error: false,
          message: ''
        },
        response: {},
        comments: [],
        comment: {},
        visibleCommentCreate: false
      }
    },
    created() {
                
    },
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
      
      let uri = `/api/tasks/${this.$route.params.id}`;
      this.axios.get(uri).then((response) => {
        this.task = response.data.data;
        if(!this.task.hasOwnProperty('error')) {
         swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к задаче", "error");
         this.$router.push({name: 'tasks'});
        }
        if(!this.task.error) {
          this.getAllComments();
        }
        else {
          swal("Ошибка", "Для задачи № " + this.task + " '" + this.title + "' " + this.message, "error");
          this.$router.push({name: 'tasks'});
        }
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
      updateTaskToNext(){
        this.task.currentProcessId = this.task.nextProcessId;
        this.task.destination = 1;
        let uri = `/api/tasks/${this.$route.params.id}`;
        this.axios.patch(uri, this.task/*{}*/)
          .then((response) => {
            this.response = response.data.data;
            if(!this.response.hasOwnProperty('error')) {
              swal("Ошибка", "Нет ответа от сервера при передаче задачи в следующий процесс", "error");
          	  this.$router.push({name: 'tasks'});
            }
            if(!this.response.error) {
              //this.$emit("changecartevent", 1);
              swal("Сохранение изменений", this.response.message, "success");
              this.$router.push({name: 'tasks'});
            }
            else {
            	swal("Ошибка", this.response.message, "error");
            	this.$router.push({name: 'tasks'});
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
              this.$router.push({name: 'tasks'});
            }
          });
      },
      updateTaskToPrev(){
        this.task.currentProcessId = this.task.prevProcessId;
        this.task.destination = 2;
        let uri = `/api/tasks/${this.$route.params.id}`;
        this.axios.patch(uri, this.task/*{}*/)
          .then((response) => {
            this.response = response.data.data;
            if(!this.response.hasOwnProperty('error')) {
              swal("Ошибка", "Нет ответа от сервера при возврате задачи в предидущий процесс", "error");
          	  this.$router.push({name: 'tasks'});
            }
            if(!this.response.error) {
              //this.$emit("changecartevent", 1);
              swal("Сохранение изменений", this.response.message, "success");
              this.$router.push({name: 'tasks'});
            }
            else {
          	  swal("Ошибка", this.response.message, "error");
          	  this.$router.push({name: 'tasks'});
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
              this.$router.push({name: 'tasks'});
            }
          });
      },
      updateTaskToClose(){
        this.task.currentProcessId = this.task.nextProcessId;
        this.task.destination = 3;
        let uri = `/api/tasks/${this.$route.params.id}`;
        this.axios.patch(uri, this.task/*{}*/)
          .then((response) => {
            this.response = response.data.data;
            if(!this.response.hasOwnProperty('error')) {
              swal("Ошибка", "Нет ответа от сервера при закрытии задачи", "error");
          	  this.$router.push({name: 'tasks'});
            }
            if(!this.response.error) {
              //this.$emit("changecartevent", 1);
              swal("Сохранение изменений", this.response.message, "success");
              this.$router.push({name: 'tasks'});
            }
            else {
          	  swal("Ошибка", this.response.message, "error");
          	  this.$router.push({name: 'tasks'});
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
              this.$router.push({name: 'tasks'});
            }
          });
      },
      getAllComments() {
        let commentsUri = `/api/comments?task=${this.task.id}`;
        this.axios.get(commentsUri)
      	  .then((response) => {
            this.comments = response.data.data;
        	  //this.meta = response.data.meta;
        });
      },
      createNewComment() {
        this.visibleCommentCreate = true;
      },
      storeNewComment(destination) {
        this.comment.taskId = this.task.id;
        let uri = '/api/comments';
        this.axios.post(uri, this.comment).then((response) => {
          this.response = response.data.data;
          if(!this.response.error) {
            switch(destination) {
              case 1:
                this.updateTaskToNext();
              break;
              
              case 2:
                this.updateTaskToPrev();
              break;
              
              case 3:
                this.updateTaskToClose();
              break;
              
              case 4:
                //const id = this.task.id;
                //this.$router.push({name: 'task-update', params: { id }});
                
                this.closeNewComment();
                this.getAllComments();
                
                //Так не работает!
                //this.$router.push({path: `/task/${this.task.id}`});
                //Колхозное обновление страницы!
                //location.reload();
              break;
              
              default:
                this.closeNewComment();
              break;
              
            }
          }
          else {
            swal("Ошибка", this.response.message, "error");
            this.closeNewComment();
          }
        })
        .catch(e => {
          if(e == 'Error: Request failed with status code 401') {
            if (localStorage.getItem('jwt')) {
              localStorage.removeItem('jwt');
              this.$router.push({name: 'login'});
            }
            //swal('Ошибка аутентификации', "Пользователь не зарегистрирован", "error");
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            this.$router.push({name: 'tasks'});
          }
        });
      },
      closeNewComment() {
        this.visibleCommentCreate = false;
        this.comment = {};
      },
      showComment(commentId) {
        console.log(commentId);
        this.$router.push({name: 'comment-details', params: {commid: commentId} });
        //this.$router.push({path: `/comment/${commentId}`});
      },
    },
    computed: {
      isSequenceLast() {
        return this.task.isSequenceLast;
      },
      isSequenceFirst() {
        return this.task.isSequenceFirst;
      },
      //visibleCommentCreate() {
        //return this.meta['canTaskCreate'];
        //return true;
      //},
    }
  }
</script>