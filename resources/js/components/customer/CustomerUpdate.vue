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
          <label class="col-sm-4 control-label">Телефон</label>
          <div class="col-sm-10">
            <input type="text" v-model="customer.phone" class="form-control" required placeholder="В формате 7xxxxxxxxxx (10 знаков после семерки)">
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
                    <tr v-for="phoneItem in customer.phones" :key="phoneItem.id">
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
        customer: {}
        
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
      
      let uri = `/api/customers/${this.$route.params.id}`;
      this.axios.get(uri).then((response) => {
        this.customer = response.data.data;
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
      updateCustomer(/*event*/){
        let uri = `/api/customers/${this.$route.params.id}`;
        this.axios.patch(uri, this.customer/*{}*/)
          .then((response) => {
            if(response.data.data) {
              //this.$emit("changecartevent", 1);
              this.$router.push({name: 'customers'});
            }
            else {
            	swal("Сохранение изменений", "Что то пошло не так...", "error");
            	this.$router.push({name: 'customers'});
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
              this.$router.push({name: 'customers'});
            }
          });
        },
        deletePhone(id) {
          if(this.customer.phones.length == 1) {
            swal("Ошибка удаления номера", "Клиента нельзя оставлять совсем без телефонных номеров!", "error");
          }
          
        },
      },
    }
</script>