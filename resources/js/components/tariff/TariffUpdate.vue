<template>
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Редактирование статуса тарифа</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="updateTariff">
      <div class="card-body">
        <div class="form-group">
          <label for="inputTariffName" class="col-sm-4 control-label">Название тарифа</label>
          <div class="col-sm-10">
            <input type="text" v-model="tariff.title" class="form-control" id="inputTariffName" disabled placeholder="Название тарифа">
          </div>
        </div>
        
        <div class="form-group">
          <label for="inputTariffDesc" class="col-sm-4 control-label">Описание тарифа</label>
          <div class="col-sm-10">
            <input type="text" v-model="tariff.description" class="form-control" id="inputTariffDesc" placeholder="Описание тарифа">
          </div>
        </div>
        
        <div class="form-group">
          <label for="inputTariffSku" class="col-sm-4 control-label">SKU тарифа</label>
          <div class="col-sm-10">
            <input type="text" v-model="tariff.sku" class="form-control" id="inputTariffSku" disabled placeholder="SKU тарифа">
          </div>
        </div>
        
        <div class="form-group">
          <label for="inputTariffSku" class="col-sm-4 control-label">Прайс</label>
          <div class="col-sm-10">
            <input type="text" v-model="tariff.price" class="form-control" id="inputTariffSku" disabled placeholder="Прайс">
          </div>
        </div>
        
        <div class="form-group">
          <label for="inputGroupName" class="col-sm-4 control-label">Статус тарифа</label>
          <div class="col-sm-10">
            <input type="checkbox" v-model="tariffStatus">
          </div>
        </div>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button class="btn btn-primary">Применить</button>
        <router-link :to="{name: 'tariffs'}" class="btn btn-default float-right">Отмена</router-link>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>
</template>


<script>
  export default {
    data(){
      return {
        tariff: {},
        message: '',
        tariffStatus: false
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
      
      this.getUpdatedTariff();
    },
    methods: {
      getUpdatedTariff() {
        let uri = `/api/tariffs/${this.$route.params.id}`;
        this.axios.get(uri).then((response) => {
          if(response.data.data) {
            this.tariff = response.data.data;
            this.setTariffStatus();
          }
          else if (response.data.message) {
            this.message = response.data.message;
            swal("Ошибка", this.message, "error");
            this.$router.push({name: 'tariffs'});
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к модифицируемому маршруту", "error");
            this.$router.push({name: 'tariffs'});
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
                this.$router.push({name: 'tariffs'});
              }
            }         
          }
          else if(error.request) {
            //console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            console.log('Внутренняя ошибка: ' + error.message);
            this.$router.push({name: 'tariffs'});
          }
        });
      },
      updateTariff(/*event*/){
        if(this.tariffStatus) {
          this.tariff.is_active = 1;
        }
        else {
          this.tariff.is_active = null;
        }
        
        let uri = `/api/tariffs/${this.$route.params.id}`;
        this.axios.patch(uri, this.tariff/*{}*/)
          .then((response) => {
            if(response.data.message) {
              this.message = response.data.message;
              swal("Сохранение изменений", this.message, "success");
              this.$router.push({name: 'tariffs'});
            }
            else {
              swal("Ошибка", "Нет ответа от сервера при сохранении изменений в тарифе", "error");
              this.$router.push({name: 'tariffs'});
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
                  this.$router.push({name: 'tariffs'});
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
              this.$router.push({name: 'tariffs'});
            }
          });
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
        setTariffStatus() {
          this.tariffStatus = this.tariff.is_active;
        },
      },
    }
</script>