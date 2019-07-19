<template>
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Настройки Телеграм - Бота</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
   
      <div class="card-body">
        <div class="form-group">
          <div class="input-group input-group-lg mb-3">
            <div class="input-group-prepend">
              <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                Действие
              </button>
              <ul class="dropdown-menu">
                <li class="dropdown-item"><a href="#" @click="storeUrlCallbackBot">Сохранить настройки url</a></li>
                <li class="dropdown-divider"></li>
                <li class="dropdown-item"><a href="#" @click="setWebhook">Активировать webhook</a></li>
                <li class="dropdown-divider"></li>
                <li class="dropdown-item"><a href="#" @click="getWebhookInfo">Получить информацию о webhook</a></li>
                <li class="dropdown-divider"></li>
                <li class="dropdown-item"><a href="#" @click="suspendWebhook">Приостановить webhook</a></li>
              </ul>
            </div>
                  <!-- /btn-group -->
            <input type="text" class="form-control" v-model="setting.url_callback_bot">
          </div>
        </div>
      </div>
  </div>
</template>


<script>
  export default {
    data(){
      return {
        settingCollection: [],
        setting: {
          url_callback_bot: null,
          url: null
        }
      }
    },
    created() {
      //
    },
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            
      let uri = '/api/telegram-index';
      this.axios.get(uri)
      	.then((response) => {
    	    this.settingCollection = response.data.data;
    	    if(response.data.data.length) {
            this.updateSettingFields();
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
            console.log(e);
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
          }
        });
    },
    methods: {
        updateSettingFields() {
          this.setting.url_callback_bot = this.settingCollection[0].value;
        },
        
        storeUrlCallbackBot(/*event*/){
          let uri = '/api/telegram-store';
          this.axios.post(uri, this.setting/*{}*/)
            .then((response) => {
              if(response.data.data) {
                //this.$emit("changecartevent", 1);
                swal("Сохранение изменений", "URI CallBack for Bot успешно отредактирован!", "success")
                .then(() => {
                  this.$router.push({name: 'bot-setting'});
                })
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
        setWebhook(/*event*/){
          this.setting.url = this.setting.url_callback_bot;
          let uri = '/api/telegram-setwebhook';
          this.axios.post(uri, this.setting/*{}*/)
            .then((response) => {
              if(response.data.setwebhook) {
                swal("WebHook установлен", response.data.setwebhook, "success")
                .then(() => {
                  this.$router.push({name: 'bot-setting'});
                })
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
        getWebhookInfo(/*event*/){
          let uri = '/api/telegram-getwebhookinfo';
          this.axios.post(uri, this.setting/*{}*/)
            .then((response) => {
              if(response.data.getwebhookinfo) {
                swal("Информация о WebHook", response.data.getwebhookinfo, "success")
                .then(() => {
                  this.$router.push({name: 'bot-setting'});
                })
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
        suspendWebhook(/*event*/){
          this.setting.url = this.setting.url_callback_bot;
          let uri = '/api/telegram-suspendwebhook';
          this.axios.post(uri, this.setting/*{}*/)
            .then((response) => {
              if(response.data.suspendwebhook) {
                swal("WebHook временно остановлен", response.data.suspendwebhook, "success")
                .then(() => {
                  this.$router.push({name: 'bot-setting'});
                })
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
      },
    }
</script>