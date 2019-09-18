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
          <textarea class="form-control" v-model="customer.description" placeholder="Комментарии по клиенту" style="height: 300px">
                      
          </textarea>
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
        customerResponse: {},
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
    },
    methods: {
      addCustomer(){
        let uri = '/api/customers';
        this.axios.post(uri, this.customer).then((response) => {
          if(!response.data.data) {
            swal("Ошибка", "Нет ответа от сервера при создании нового клиента", "error");
          }
          this.customerResponse = response.data.data;
          if(!this.customerResponse.hasOwnProperty('error')) {
            swal("Ошибка", "Неверный формат ответа сервера при создании нового клиента", "error");
            this.$router.push({name: 'tasks'});
          }
          if(!this.customerResponse.error) {
            //this.$emit("changecartevent", 1);
            swal("Сохранение изменений", this.customerResponse.message, "success");
            if(this.optionLeavePage) {
              this.$router.push({name: 'contracts-for-customer', params: {customid: this.customerResponse.customer.id}});
            }
            else {
              this.$router.push({name: 'customers'});
            }
          }
          else {
            swal("Ошибка", this.customerResponse.message, "error");
            this.$router.push({name: 'search-customer'});
          }
          
          
          //if(response.data.data.id) {
          //  swal("Заказ", 'Новый клиент "' + this.customer.surname + ' ' + this.customer.name + '" успешно создан', "success");
          //  if(this.optionLeavePage) {
          //    this.$router.push({name: 'contracts-for-customer', params: {customid: this.customer.id}});
          //  }
          //  else {
          //    this.$router.push({name: 'customers'});
          //  }
          //}
          //else {
          //  swal("Сохранение изменений", "Что то пошло не так...", "error");
          //  if(this.optionLeavePage) {
          //    this.$router.push({name: 'search-customer'});
          //  }
          //  else {
          //    this.$router.push({name: 'customers'});
          //  }
          //}
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
            if(this.optionLeavePage) {
              this.$router.push({name: 'search-customer'});
            }
            else {
              this.$router.push({name: 'customers'});
            }
          }
        });
      },
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