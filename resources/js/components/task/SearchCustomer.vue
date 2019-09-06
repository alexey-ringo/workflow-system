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
          <input type="text" v-model="keywordsSurname" class="form-control" placeholder="Фамилия клиента">
          <ul v-if="resultsSurname.length > 0">
            <li v-for="result in resultsSurname" :key="result.id" v-text="result.surname"></li>
          </ul>
        </div>
      </div>
      
      <div class="form-group">
        <label class="col-sm-4 control-label">Телефон клиента</label>
        <div class="col-sm-10">
          <input type="text" v-model="keywordsPhone" class="form-control" placeholder="В формате 7xxxxxxxxxx (10 знаков после семерки)">
          
        </div>
      </div>
      
      <router-view v-bind:customerProp="customerByPhone"></router-view>
      
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
        customerByPhone: {},
        keywordsPhone: null,
        keywordsSurname: null,
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
        if(this.keywordsPhone.length === 11) {
          this.fetchPhone();
        }
      },
      keywordsSurname(after, before) {
        this.fetchSurname();
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
                this.customerByPhone = response.data.data;
                this.phoneNotFound = false;
                this.defaultInputs = false;
                this.$router.push({name: 'contracts-for-customer', params: {customid: this.customerByPhone.id}});
              }
              else {
                this.customerByPhone = {};
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
            this.$router.push({name: 'tasks'});
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
              	this.resultsSurname = response.data.data;
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
        this.customerByPhone = {};
        this.resultsSurname = [];
        this.phoneNotFound = false;
        this.defaultInputs = true;
      },
      isRouteForCurrentCustomer(routeVal) {
        if(routeVal == 1) {
          return false;
        }
        else {
          return true;
        }
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
      }
    },
  }
</script>