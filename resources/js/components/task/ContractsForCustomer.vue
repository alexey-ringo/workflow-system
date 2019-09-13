<template>
  <!-- Horizontal Form -->
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Все договора клиента {{ customer.name + ' ' + customer.surname }}</h3>
    </div>
    <!-- /.card-header -->
   
    
    
    
    
    
      <div v-if="!isEmptyObject(customer)" class="card-body">
        
        <ul>
            <li>ФИО клиента:
              <ul>
                <li>Фамилия: <b>{{ customer.surname }}</b></li>
                <li>Имя: <b>{{ customer.name }}</b></li>
                <li>Отчество: <b>{{ customer.second_name }}</b></li>
              </ul>
            </li>
            <li>Адрес клиента:
              <ul>
                <li>Город: <b>{{ customer.city }}</b></li>
                <li>Улица: <b>{{ customer.street }}</b></li>
                <li>Дом: <b>{{ customer.building }}</b></li>
                <li>Квартира: <b>{{ customer.flat }}</b></li>
              </ul>
            </li>
            <li>Телефоны клиента:
              <ul>
                <li v-for="phoneItem in customer.phones" :key="phoneItem.id" v-text="phoneItem.phone"></li>
              </ul>
            </li>
          </ul>
        
        
        <div class="col-sm-4 table-responsive p-0" v-if="!visibleTaskForNewContract">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Контракты</th>
                        <th>Выбрать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="contractItem in customer.contracts" :key="contractItem.id">
                        <td>{{ contractItem.contract_num  }}</td>
                        <td>
                          <router-link :to="{name: 'create-task-for-exists-contract', params: {contractid: contractItem.id}}" class="btn btn-xs btn-default">
                                Создать обращение
                          </router-link>
                        </td>
                    </tr>
                    <tr>
                      <td>Создать новый контракт</td>
                      <td>
                        <button type="button" class="btn btn-xs btn-default" @click="addContract">Новый контракт</button>
                      </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div v-if="visibleTaskForNewContract">
          <div class="form-group">
            <label for="inputTaskDesc" class="col-sm-4 control-label">Краткое описание</label>
            <div class="col-sm-10">
              <input type="text" v-model="task.description" class="form-control" placeholder="Краткое описание">
            </div>
          </div>
          <button class="btn btn-primary" @click="createContract">Новая задача для нового контракта</button>
          <button class="btn btn-default float-right" @click="setDefault">Отмена</button>
          
        </div>
                  
        
      </div>
      <!-- /.card-body -->
      
   
    
    
    
    
  </div>
  <!-- /.card -->
</template>

<script>
  export default {
    props: {
      customerProp: {}
    },
    data() {
      return {
        customer: {},
        task: {},
        contractResponse: {},
        visibleTaskForNewContract: false
      }
    },
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json';
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
      this.update();
    },
    methods: {
      update() {
        if(this.isEmptyObject(this.customerProp)) {
          this.getCustomer();
        }
        else {
          this.customer = this.customerProp;
        }
      },
      getCustomer() {
        let uri = `/api/customers/${this.$route.params.customid}`;
        this.axios.get(uri).then((response) => {
          this.customer = response.data.data;
          if(this.isEmptyObject(this.customer)) {
            swal('Ошибка', 'Клиент с id "' + this.$route.params.customid + '" не найден!', 'error');
            this.$router.push({name: 'search-customer'});
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
            swal('Ошибка', "Внутренняя ошибка сервера при чтении данных клиента", "error");
            this.$router.push({name: 'search-customer'});
          }
        });
      },
      addContract() {
        this.visibleTaskForNewContract = true;
      },
      createContract() {
        let uri = '/api/contracts';
        
        this.customer.task_route = 1;
        this.customer.task_description = this.task.description;
        
        this.axios.post(uri, this.customer).then((response) => {
          if(!response.data.data) {
            swal("Ошибка", "Нет ответа от сервера при создании нового контракта", "error");
          }
          this.contractResponse = response.data.data;
          if(!this.contractResponse.hasOwnProperty('error')) {
            swal("Ошибка", "Неверный формат ответа сервера при создании нового контракта", "error");
          }
          if(!this.contractResponse.error) {
            this.setDefault();
            swal("Сохранение изменений", this.contractResponse.message, "success");
          }
          else {
            swal("Ошибка", this.contractResponse.message, "error");
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
            //this.$router.push({name: 'customers'});
          }
        });
      },
      setDefault() {
        this.visibleTaskForNewContract = false;
        this.task = {};
        this.getCustomer();
      },
      isEmptyObject(obj) {
        for (var i in obj) {
          if (obj.hasOwnProperty(i)) {
            return false;
          }
        }
      return true;
      },
    },
  }
</script>