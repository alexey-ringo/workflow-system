<template>
  <!-- Horizontal Form -->
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Новый клиент</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="addCustomer">
      <div class="card-body">
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Фамилия</label>
          <div class="col-sm-10">
            <input type="text" v-model="customer.surname" class="form-control" required placeholder="Фамилия нового клиента">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Имя</label>
          <div class="col-sm-10">
            <input type="text" v-model="customer.name" class="form-control" required placeholder="Имя нового клиента">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Отчество</label>
          <div class="col-sm-10">
            <input type="text" v-model="customer.second_name" class="form-control" placeholder="Отчество нового клиента">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Город</label>
          <div class="col-sm-10">
            <input type="text" v-model="customer.city" class="form-control" required placeholder="Город">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Улица</label>
          <div class="col-sm-10">
            <input type="text" v-model="customer.street" class="form-control" required placeholder="Улица">
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="col-sm-4 control-label">Дом</label>
              <input type="text" v-model="customer.building" class="form-control" required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="col-sm-4 control-label">Квартира</label>
              <input type="text" v-model="customer.flat" class="form-control" required>
            </div>
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Телефон</label>
          <div class="col-sm-10">
            <input type="text" v-model="customer.phone" class="form-control" required placeholder="В формате 7xxxxxxxxxx (10 знаков после семерки)">
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Email</label>
          <div class="col-sm-10">
            <input type="email" v-model="customer.email" class="form-control" required placeholder="Email">
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Тариф</label>
          <div class="col-sm-10">
            <select v-model="selectTariff" required>
              <option v-for="tariff in tariffs" :value="tariff.id" :key="tariff.id">
                {{ tariff.title + ' ' + tariff.price }}
              </option>
            </select>
          </div>
        </div>
                     
        <div class="form-group">
          <label class="col-sm-4 control-label">Первоначальный комментарий к новой задаче по новому контракту</label>
          <div class="col-sm-10">
            <textarea class="form-control" v-model="customer.task_comment" placeholder="Комментарии по клиенту" style="height: 300px">
            </textarea>
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Комментарий по клиенту</label>
          <div class="col-sm-10">
            <textarea class="form-control" v-model="customer.description" placeholder="Комментарии по клиенту" style="height: 300px">
            </textarea>
          </div>
        </div>
        
      </div>
      <!-- /.card-body -->
  
      <div class="card-footer">
        <button class="btn btn-primary">Создать</button>
        <router-link :to="{name: 'customers'}" class="btn btn-default float-right" v-if="!optionLeavePage">Отмена</router-link>
        <router-link :to="{name: 'search-customer'}" class="btn btn-default float-right" v-if="optionLeavePage">Вернуться</router-link>
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
        customer: {},
        tariffs: [],
        message: '',
        selectTariff: false,
        optionLeavePage: false
      }
    },
    /*
    create() {
    },
    */
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json';
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
      
      this.getTariffs();
    },
    methods: {
      getTariffs() {
        let uri = '/api/tariffs';
        this.axios.get(uri).then((response) => {
          if(response.data.data) {
            this.tariffs = response.data.data;
          }
          else if (response.data.message) {
            this.message = response.data.message;
            swal("Ошибка", this.message, "error");
            this.leavePageRules();
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к списку тарифов", "error");
            this.leavePageRules();
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
                this.leavePageRules();
              }
            }         
          }
          else if(error.request) {
            //console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            console.log('Внутренняя ошибка: ' + error.message);
            this.leavePageRules();
          }
        });
      },
      addCustomer(){
        this.customer.contract_tariff_id = this.selectTariff;
        
        let uri = '/api/customers';
        this.axios.post(uri, this.customer).then((response) => {
          if(response.data.message) {
            this.message = response.data.message;                            
            swal("Сохранение изменений", this.message, "success");
            this.leavePageRules();  
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при создании нового клиента", "error");
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
                this.leavePageRules();
              }
            }//Ошибки валидации
            else {
              swal('Ошибка - ' + error.response.status, this.errMessageToStr(error.response.data), "error");
            }
          }
          else if(error.request) {
            //console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            console.log('Внутренняя ошибка: ' + error.message);
            this.leavePageRules();
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
      leavePageRules() {
        if(this.optionLeavePage) {
          this.$router.push({name: 'search-customer'});
        }
        else {
          this.$router.push({name: 'customers'});
        }
      }
    },
    beforeRouteEnter(to, from, next) {
      if(from.name == 'customer-not-found') {
        next(vm => {
          // экземпляр компонента доступен как `vm`
          vm.optionLeavePage = true;
        });
      }
      else {
        next();
      }
    },
  }
</script>