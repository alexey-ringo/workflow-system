<template>
  <div class="card card-info">
    <div class="card-header">
      <div class="row">
        <div class="col-md-8">
          <h3 v-if="!isSequenceLast" class="card-title">Обработка заявки № <b>{{task.task}}</b>
                                                         в процессе: <b>{{task.processName}}</b></h3>
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
          <button class="btn btn-primary" v-bind:disabled="isEmptyComment" v-if="!isSequenceFirst" @click="updateTask(2)">Назад в <b>{{task.prevProcessName}}</b></button>
          <button class="btn btn-primary" v-bind:disabled="isEmptyComment" v-if="!isSequenceLast" @click="updateTask(1)">Вперед в <b>{{task.nextProcessName}}</b></button>
          <button class="btn btn-primary" v-bind:disabled="isEmptyComment" @click="storeComment()">Просто комментарий</button>
          <button class="btn btn-primary" v-bind:disabled="isEmptyComment" v-if="isSequenceLast" @click="updateTask(3)">Закрыть заявку</button>
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
        task: {},                
        message: '',        
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
      
      this.getUpdatedTask();
    },
    methods: {
      getUpdatedTask() {
        let uri = `/api/tasks/${this.$route.params.id}`;
        this.axios.get(uri).then((response) => {
          if(response.data.data) {
            this.task = response.data.data;        
            this.getAllComments();
          }
          else if (response.data.message) {
            this.message = response.data.message;
            swal("Ошибка", this.message, "error");
            this.$router.push({name: 'tasks'});
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к модифицируемой задаче", "error");
            this.$router.push({name: 'tasks'});
          }        
        })
        .catch(error => {
          if(error.response) {
            if(error.response.data.message) {
              if(error.response.status == 401) {
                if (localStorage.getItem('jwt')) {
                  localStorage.removeItem('jwt');
                  this.$router.push({name: 'login'});
                }
              }
              else {
                swal('Ошибка - ' + error.response.status, error.response.data.message, "error");
                this.$router.push({name: 'tasks'});
              }
            }         
          }
          else if(error.request) {
            //console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            console.log('Внутренняя ошибка: ' + error.message);
            this.$router.push({name: 'tasks'});
          }
        });
      },
      updateTask(destination) {
        if(this.isEmptyComment) {
          return;
        }
        
        let errNoResponseMsg = '';
        switch(destination) {
          case 1:
            this.task.currentProcessId = this.task.nextProcessId;
            errNoResponseMsg = 'Нет ответа от сервера при передаче задачи в следующий процесс';
          break;
            
          case 2:
            this.task.currentProcessId = this.task.prevProcessId;
            errNoResponseMsg = 'Нет ответа от сервера при возврате задачи в предидущий процесс';
          break;
              
          case 3:
            this.task.currentProcessId = this.task.nextProcessId;
            errNoResponseMsg = 'Нет ответа от сервера при закрытии задачи';
          break;
          
          default:
          errNoResponseMsg = 'Неправильно указано направление движения процесса задачи';
            swal("Ошибка", errNoResponseMsg, "error");
            //this.closeNewComment();
            //this.getAllComments();
            this.$router.push({name: 'tasks'});
          break;
        }
        
        this.task.comment = this.comment.comment;
        this.task.destination = destination;
        
        let uri = `/api/tasks/${this.$route.params.id}`;
        this.axios.patch(uri, this.task/*{}*/)
          .then((response) => {
            if(response.data.message) {
              this.message = response.data.message;
              swal("Сохранение изменений", this.message, "success");
              this.$router.push({name: 'tasks'});
            }
            else {
              swal("Ошибка", errNoResponseMsg, "error");
              this.$router.push({name: 'tasks'});
            }          
          })
          .catch(error => {
            if(error.response) {
              if(error.response.data.message) {
                if(error.response.status == 401) {
                  if (localStorage.getItem('jwt')) {
                    localStorage.removeItem('jwt');
                    this.$router.push({name: 'login'});
                  }
                }
                else {
                  swal('Ошибка - ' + error.response.status, error.response.data.message, "error");
                  this.$router.push({name: 'tasks'});
                }
              }//Ошибки валидации
              else {
                swal('Ошибка - ' + error.response.status, this.errMessageToStr(error.response.data), "error");
              }
            }
            else if(error.request) {
              console.log(error.request.data);
            }
            else {
              swal('Ошибка', "Внутренняя ошибка сервера", "error");
              console.log('Внутренняя ошибка: ' + error.message);
              this.$router.push({name: 'tasks'});
            }
          });
      },
      getAllComments() {
        let commentsUri = `/api/comments?task=${this.task.id}`;
        this.axios.get(commentsUri)
      	  .then((response) => {
      	    if(response.data.data) {
      	      this.comments = response.data.data;
        	    //this.meta = response.data.meta;
      	    }
      	    else if (response.data.message) {
              this.message = response.data.message;
              swal("Ошибка", this.message, "error");
              this.$router.push({name: 'tasks'});
            }
            else {
              swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к комментариям по модифицируемой задаче", "error");
              this.$router.push({name: 'tasks'});
            }
          })
          .catch(error => {
          if(error.response) {
            if(error.response.data.message) {
              if(error.response.status == 401) {
                if (localStorage.getItem('jwt')) {
                  localStorage.removeItem('jwt');
                  this.$router.push({name: 'login'});
                }
              }
              else {
                swal('Ошибка - ' + error.response.status, error.response.data.message, "error");
                this.$router.push({name: 'tasks'});
              }
            }         
          }
          else if(error.request) {
            //console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            console.log('Внутренняя ошибка: ' + error.message);
            this.$router.push({name: 'tasks'});
          }
        });
      },
      createNewComment() {
        this.visibleCommentCreate = true;
      },
      storeComment() {
        this.comment.taskId = this.task.id;
        let uri = '/api/comments';
        this.axios.post(uri, this.comment).then((response) => {
          if(response.data.message) {
            this.message = response.data.message;
            swal("Сохранение нового комментария", this.message, "success");
            this.closeNewComment();
            this.getAllComments();
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при сохранении комментария", "error");
            //this.$router.push({name: 'tasks'});
            this.closeNewComment();
            this.getAllComments();
          }
        })
        .catch(error => {
          if(error.response) {
            if(error.response.data.message) {
              if(error.response.status == 401) {
                if (localStorage.getItem('jwt')) {
                  localStorage.removeItem('jwt');
                  this.$router.push({name: 'login'});
                }
              }
              else {
                swal('Ошибка - ' + error.response.status, error.response.data.message, "error");
                //this.$router.push({name: 'tasks'});
                this.closeNewComment();
                this.getAllComments();
              }
            }//Ошибки валидации
            else {
              swal('Ошибка - ' + error.response.status, this.errMessageToStr(error.response.data), "error");
              this.closeNewComment();
              this.getAllComments();
            }
          }
          else if(error.request) {
            //console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            //this.$router.push({name: 'tasks'});
            this.closeNewComment();
            this.getAllComments();
          }
        });
      },
      closeNewComment() {
        this.visibleCommentCreate = false;
        this.comment = {};
      },
      showComment(commentId) {
        this.$router.push({name: 'comment-details', params: {commid: commentId} });
        //this.$router.push({path: `/comment/${commentId}`});
      },
      isEmptyObject(obj) {
        for (var i in obj) {
          if (obj.hasOwnProperty(i)) {
            return false;
          }
        }
      return true;
      },
      errMessageToStr(errors) {
          let result = '';
          for(let key in errors) {
            errors[key].forEach(function(item){
              result += item + '; ';
            });
          }
          return result;
      },
    },
    computed: {
      isSequenceLast() {
        return this.task.isSequenceLast;
      },
      isSequenceFirst() {
        return this.task.isSequenceFirst;
      },      
      isEmptyComment() {
      //  if(this.comment.hasOwnProperty('comment')) {
      //    if(this.comment.comment.length == 0) {
      //      return true;
      //    }
      //    return false;
      //  }
      //  return true;
      
        if(this.isEmptyObject(this.comment)) {
          return true;
        }
        else {
          if(this.comment.comment.length == 0) {
            return true;
          }
          return false;
        }
      }
      //visibleCommentCreate() {
        //return this.meta['canTaskCreate'];
        //return true;
      //},
    }
  }
</script>