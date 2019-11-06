<template>
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Редактирование карточки клиента</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal" @submit.prevent="updateCustomer">
      <div class="card-body">
        
        <div class="form-group">
          <label class="col-sm-4 control-label">Фамилия</label>
          <div class="col-sm-10">
            <input type="text" v-model="customer.surname" class="form-control" required disabled placeholder="Фамилия нового клиента">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Имя</label>
          <div class="col-sm-10">
            <input type="text" v-model="customer.name" class="form-control" required disabled placeholder="Имя нового клиента">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Отчество</label>
          <div class="col-sm-10">
            <input type="text" v-model="customer.second_name" class="form-control" disabled placeholder="Отчество нового клиента">
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
          <label class="col-sm-2 control-label">Телефон</label>
        </div>
        
        <div class="row">
          <div class="col-md-2" v-if="!visibleAddPhone">
            <button type="button" class="btn btn-danger" @click="showAddPhone">Добавить телефон</button>
          </div>
          <div class="col-md-2" v-if="visibleAddPhone">
            <button type="button" class="btn btn-primary" @click="addPhone">Применить</button>
          </div>
          <div class="col-md-10" v-if="visibleAddPhone">
            <div class="form-group">
              <input type="text" v-model="phone.phone" class="form-control" required placeholder="В формате 7xxxxxxxxxx (10 знаков после семерки)">
            </div>
          </div>
        </div>
        
        <div class="col-sm-4 table-responsive p-0">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Телефон</th>
                        <th>Редактировать</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="phoneItem in phones" :key="phoneItem.id">
                        <td>{{ phoneItem.phone  }}</td>
                        <td>
                            <router-link :to="{name: 'customer-update', params: {id: customer.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <button class="btn btn-danger" @click.prevent = "deletePhone(phoneItem.id)">Удалить</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Телефон</th>
                        <th>Редактировать</th>
                    </tr>
                </tfoot>
            </table>
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
        <button class="btn btn-primary">Применить</button>
        <router-link :to="{name: 'customers'}" class="btn btn-default float-right">Отмена</router-link>
      </div>
      <!-- /.card-footer -->
    </form>
  </div>
</template>


<script>
  export default {
    data(){
      return {
        customer: {},
        message: '',
        phones: [],
        phone: {},
        visibleAddPhone: false
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
      
      this.getUpdatedCustomer();
    },
    methods: {
      getUpdatedCustomer() {
        let uri = `/api/customers/${this.$route.params.id}`;
        this.axios.get(uri).then((response) => {
          if(response.data.data) {
            this.customer = response.data.data;
            this.getPhones();
          }
          else if (response.data.message) {
            this.message = response.data.message;
            swal("Ошибка", this.message, "error");
            this.$router.push({name: 'customers'});
          }
          else {
            swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к модифицируемой карточке клиента", "error");
            this.$router.push({name: 'customers'});
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
                this.$router.push({name: 'customers'});
              }
            }         
          }
          else if(error.request) {
            //console.log(error.request.data);
          }
          else {
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
            console.log('Внутренняя ошибка: ' + error.message);
            this.$router.push({name: 'customers'});
          }
        });
      },
      updateCustomer(/*event*/){
        let uri = `/api/customers/${this.$route.params.id}`;
        this.axios.patch(uri, this.customer/*{}*/)
          .then((response) => {
            if(response.data.message) {
              this.message = response.data.message;
              swal("Сохранение изменений", this.message, "success");
              this.$router.push({name: 'customers'});
            }
            else {
              swal("Ошибка", "Нет ответа от сервера при сохранении изменений в карточке клиента", "error");
              this.$router.push({name: 'customers'});
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
                  this.$router.push({name: 'customers'});
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
              this.$router.push({name: 'customers'});
            }
          });
        },
        getPhones() {
          let uri = `/api/phones/${this.customer.id}`;
          this.axios.get(uri).then((response) => {
            this.phones = response.data.data;
          })
          .catch(e => {
            //console.log(e);
            swal('Ошибка', "Внутренняя ошибка сервера", "error");
          });
        },
        showAddPhone() {
          this.visibleAddPhone = true;
        },
        addPhone() {
          this.phone.customer_id = this.customer.id;
          let uri = '/api/phones';
          this.axios.post(uri, this.phone).then((response) => {
            if(response.data.message) {
              this.message = response.data.message;                            
              swal("Сохранение изменений", this.message, "success");
              this.getPhones();
              this.phone = {};
              this.visibleAddPhone = false;  
            }
            else {
              swal("Ошибка", "Нет ответа от сервера при добавлении телефона клиента", "error");
              this.phone = {};
              this.visibleAddPhone = false;
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
                  this.phone = {};
                  this.visibleAddPhone = false;
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
              this.phone = {};
              this.visibleAddPhone = false;
            }
          });
        },
        deletePhone(id) {
          if(this.phones.length == 1) {
            swal("Ошибка удаления номера", "Клиента нельзя оставлять совсем без телефонных номеров!", "error");
            return false;
          }
          let uri = `/api/phones/${id}`;
          if (confirm("Do you really want to delete it?")) {
            this.axios.delete(uri)
              .then((response) => {
                if(response.data.data) {
                  this.getPhones();
                }
                else if (response.data.message) {
                  this.message = response.data.message;
                  swal("Ошибка", this.message, "error");
                }
                else {
                  swal("Ошибка", "Нет ответа от сервера при попытке удаления выбранного телефона", "error");
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
                    }
                  }         
                }
                else if(error.request) {
                }
                else {
                  swal('Ошибка', "Внутренняя ошибка сервера", "error");
                }
              });
            }
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
    }
</script>