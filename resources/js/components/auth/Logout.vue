<template>
 
</template>

<script>
    export default {
        data(){
           return {
            
                }
        },
        methods : {

            },
        mounted(){
            //localStorage.setItem('jwt', null);
            //console.log(localStorage.getItem('jwt'));
            
            if (localStorage.getItem('jwt') != null){
                                
                            
            let token = localStorage.getItem('jwt');

            axios.defaults.headers.common['Content-Type'] = 'application/json';
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

            axios.get('api/logout').
                then((response) => {
                    //console.log(response.data);
                    localStorage.removeItem('jwt');
                    this.$router.push({name: 'login'});
                })
                .catch(e => {
                    	//console.log(e);
                    	localStorage.removeItem('jwt');
                    });
                    
            //this.$router.go('/board')
            }
        },
            //beforeRouteEnter (to, from, next) { 
            //    if (localStorage.getItem('jwt')) {
            //        return next('board');
            //    }

//                next();
//            }
        beforeRouteEnter (to, from, next) { 
            if (!localStorage.getItem('jwt')) {
                return next('login');
            }

            next();
        }
    }
    </script>    