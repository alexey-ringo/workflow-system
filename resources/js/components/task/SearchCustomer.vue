<template>
  <!-- Horizontal Form -->
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Поиск по клиенту</h3>
    </div>
    <!-- /.card-header -->
    
    <div class="card-body">
      <div class="form-group">
        <label class="col-sm-4 control-label">Фамилия клиента</label>
        <div class="col-sm-10">
          <div class="input-group">
            <input type="text" class="form-control" v-model="keywordsSurname" placeholder="Фамилия клиента">
            <span class="input-group-append">
              <button type="button" class="btn btn-info btn-flat" @click="refreshResults">Обновить</button>
            </span>
          </div>
          <ul v-if="visibleFoundCustomersSurname">
            <li v-for="result in resultsSurname" :key="result.id">
              <router-link :to="{name: 'contracts-for-customer', params: {customid: result.id}}" @click.native="sendCustomer(result)">
                {{ result.surname + ' ' + result.name + ' город: ' + result.city + ' ул. ' + result.street + ' дом ' + result.building + ' кв. ' + result.flat }}
              </router-link>
            </li>
          </ul>
        </div>
      </div>
      
      <div class="form-group">
        <label class="col-sm-4 control-label">Телефон клиента</label>
        <div class="col-sm-10">
          <div class="input-group">
            <input type="text" class="form-control" v-model="keywordsPhone" placeholder="В формате 7xxxxxxxxxx (10 знаков после семерки)">
            <span class="input-group-append">
              <button type="button" class="btn btn-info btn-flat" @click="refreshResults">Обновить</button>
            </span>
          </div>
        </div>
      </div>
      
      <router-view v-bind:customerProp="foundCustomer"></router-view>
      
    </div>
    <!-- /.card-body -->
    
    
    <div v-if="defaultInputs" class="card-footer">
      <router-link :to="{name: 'tasks'}" class="btn btn-default float-right">Отмена</router-link>
    </div>
    <!-- /.card-footer -->
    
  </div>
  <!-- /.card -->
</template>

<script>
  export default {
    data(){
      return {
        foundCustomer: {},
        keywordsPhone: '',
        keywordsSurname: '',
        resultsSurname: [],
        phoneNotFound: false,
        defaultInputs: true
      }
    },
    mounted() {
      let token = localStorage.getItem('jwt')

      this.axios.defaults.headers.common['Content-Type'] = 'application/json'
      this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
      
    },
    watch: {
      keywordsPhone(after, before) {
        if(this.keywordsPhone.length == 11) {
          this.fetchPhone();
        }
      },
      keywordsSurname(after, before) {
        if(this.keywordsSurname.length > 2) {
          this.fetchSurname();
        }
      }
    },
    methods: {
      fetchPhone() {
        //this.axios.get('/api/search-phone', { params: { keywords: this.keywords } })
        //  .then(response => this.results = reponse.data)
        //  .catch(error => {});
        let uri = '/api/search-phone';
          this.axios.get(uri, { params: { keywords: this.keywordsPhone }})
          	.then((response) => {
              if(response.data.data) {
                this.foundCustomer = response.data.data;
                this.phoneNotFound = false;
                this.defaultInputs = false;
                this.$router.push({name: 'contracts-for-customer', params: {customid: this.foundCustomer.id}});
              }
              else {
                this.foundCustomer = {};
                this.phoneNotFound = true;
                this.defaultInputs = false;
                this.$router.push({name: 'customer-not-found'});
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
            this.$router.push({name: 'dashboard'});
          }
        });
      },
      fetchSurname() {
        //this.axios.get('/api/search-phone', { params: { keywords: this.keywords } })
        //  .then(response => this.results = reponse.data)
        //  .catch(error => {});
        let uri = '/api/search-surname';
          this.axios.get(uri, { params: { keywords: this.keywordsSurname }})
          	.then((response) => {
              if(response.data.data) {
                this.resultsSurname = response.data.data;
              }
              else {
                this.resultsSurname = [];
              }
            })
            .catch(e => {
            	//console.log(e);
            	swal('Ошибка', "Внутренняя ошибка сервера", "error");
            });
      },
      isEmptyObject(obj) {
        for (var i in obj) {
          if (obj.hasOwnProperty(i)) {
            return false;
          }
        }
      return true;
      },
      setDefault() {
        this.keywordsPhone = null;
        this.keywordsSurname = null;
        this.foundCustomer = {};
        this.resultsSurname = [];
        this.phoneNotFound = false;
        this.defaultInputs = true;
      },
      sendCustomer(customer) {
        this.foundCustomer = customer;
      },
      refreshResults() {
        this.resultsSurname = [];
        //Vue.set(this.keywordsSurname = '');
        this.keywordsSurname = '';
        this.keywordsPhone = '';
        if(!this.isEmptyObject(this.foundCustomer)) {
          this.foundCustomer = {};
        }
        this.$router.push({name: 'search-customer'});
      },
    },
    computed: {
      isEmptyCustomerByPhone() {
        for (var i in this.customerByPhone) {
          if (this.customerByPhone.hasOwnProperty(i)) {
            return false;
          }
        }
      return true;
      },
      visibleFoundCustomersSurname() {
        if(this.resultsSurname.length > 0) {
          return true;
        }
        else {
          return false;
        }
      }
    },
  }
</script>