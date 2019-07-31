<template>
  <div class="table-responsive mailbox-messages">
    <table class="table table-hover table-striped">
      <tbody>
        <tr>
          <td class="mailbox-date">5 mins ago</td>
          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
          <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to this problem...
          </td>
          <td class="mailbox-attachment"></td>
        </tr>
      </tbody>
    </table>
    <!-- /.table -->
  </div>
  <!-- /.mail-box-messages -->
</template>

<script>
    export default {
        data: function () {
            return {
                comments: []
            }
        },
        mounted() {
            let token = localStorage.getItem('jwt')

            this.axios.defaults.headers.common['Content-Type'] = 'application/json'
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            
            let uri = '/api/comments';
            this.axios.get(uri)
            	.then((response) => {
            	    this.comments = response.data.data;
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
            
        },
        computed: {
            
        },
        beforeRouteEnter (to, from, next) { 
            if ( ! localStorage.getItem('jwt')) {
                return next('login')
            }
            next()
        }
    }
    
</script>