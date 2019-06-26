<template>
    <router-view></router-view>
</template>

<script>
    export default {
        data(){
            return {
                //isLoggedIn : null,
                //name : null
            }
        },
        mounted(){
            console.log('App примонтирован')
            //this.isLoggedIn = localStorage.getItem('jwt')
            //this.name = localStorage.getItem('user')
            //if(!localStorage.getItem('jwt') && localStorage.getItem('user')) {
            //    this.$router.go('/');
            //}
            
        },
        /*
        beforeRouteEnter (to, from, next) { 
                if ( ! localStorage.getItem('jwt') && localStorage.getItem('user')) {
                    return next('login')
                }

                next()
            }*/
    }
</script>